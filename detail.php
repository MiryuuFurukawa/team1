<?php
require_once 'function.php';
startSessionIfNeeded();

$referer = $_SERVER['HTTP_REFERER'] ?? '';
if (
    $referer === '' ||
    (strpos($referer, 'questions.php') === false &&
        strpos($referer, 'detail.php') === false &&
        strpos($referer, 'answer.php') === false &&
        strpos($referer, 'questionEdit.php') === false &&
        strpos($referer, 'answerEdit.php') === false &&
        strpos($referer, 'index.php') === false
    )
) {
    header('Location: questions.php');
    exit;
}

$num = 5;

$questionId = $_GET['questionId'] ?? '';
// 一覧から引き継いだページ番号を q_page で受け取る（なければ '1'）
$questionPage = $_GET['q_page'] ?? '1';
$keyword = $_GET['keyword'] ?? '';

if ($questionId === '' || !ctype_digit((string) $questionId)) {
    header('Location: questions.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    requireLogin();
    $answerId = $_POST['answerId'] ?? '';

    if ($answerId !== '' && ctype_digit((string) $answerId)) {
        $targetAnswer = getAnswerById((int) $answerId);

        if ($targetAnswer && (int) $targetAnswer['userId'] === (int) $_SESSION['user']['id']) {
            deleteAnswer((int) $answerId);
        }
    }

    $redirectUrl = 'detail.php?questionId=' . urlencode($questionId) . '&q_page=' . urlencode($questionPage);
    if (isset($_GET['page']) && $_GET['page'] !== '1') {
        $redirectUrl .= '&page=' . urlencode($_GET['page']);
    }
    if ($keyword !== '') {
        $redirectUrl .= '&keyword=' . urlencode($keyword);
    }
    header('Location: ' . $redirectUrl);
    exit;
}

$loginUser = $_SESSION['user'] ?? null;

$question = getQuestionById((int) $questionId);
if ($question === false) {
    header('Location: questions.php');
    exit;
}
$question['name'] = $question['userName'] ?? '';

$answers = getAnswersByQuestionId((int) $questionId);
if ($answers === false) {
    $answers = [];
}

// 現在のページ番号（回答一覧用）とOFFSETを計算
$page = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($page - 1) * $num;
$pagedAnswers = array_slice($answers, $offset, $num);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>質問詳細画面</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1><a href="questions.php">アイデア倉庫</a></h1>
        <div class="header-btn">
            <form action="questions.php" method="GET">
                <input type="submit" id="other-btn" value="質問一覧">
            </form>
            <form action="questionInput.php" method="GET">
                <input type="submit" id="other-btn" value="質問する">
            </form>
            <?php if (isLoggedIn()): ?>
                <form action="index.php" method="POST">
                    <input type="submit" id="logout-btn" name="logout" value="ログアウト">
                </form>
            <?php else: ?>
                <form action="index.php" method="POST">
                    <input type="hidden" name="page" value="detail">
                    <input type="hidden" name="questionId"
                        value="<?= htmlspecialchars((string) $questionId, ENT_QUOTES, 'UTF-8') ?>">
                    <?php if ($questionPage !== '1'): ?>
                        <input type="hidden" name="page_no"
                            value="<?= htmlspecialchars((string) $questionPage, ENT_QUOTES, 'UTF-8') ?>">
                    <?php endif; ?>
                    <?php if ($keyword !== ''): ?>
                        <input type="hidden" name="keyword"
                            value="<?= htmlspecialchars((string) $keyword, ENT_QUOTES, 'UTF-8') ?>">
                    <?php endif; ?>
                    <input type="submit" id="other-btn" name="login" value="ログイン">
                </form>
            <?php endif; ?>
        </div>
    </header>

    <main>
        <h2 id="question-h2">Question</h2>
        <div class="question-container">
            <div class="question-name"><?= htmlspecialchars((string) $question['name'], ENT_QUOTES, 'UTF-8') ?>
            </div>
            <div class="question-time">
                <?= date('Y年m月d日 H時i分', strtotime($question['date'])) ?>
            </div>
            <div class="question-text">
                <?= nl2br(htmlspecialchars((string) $question['question'], ENT_QUOTES, 'UTF-8')) ?>
            </div>
            <div class="question-container-btn">
                <form action="answer.php" method="GET">
                    <input type="hidden" name="questionId"
                        value="<?= htmlspecialchars((string) $question['id'], ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="page" value="answer">
                    <input type="submit" value="回答" name="answer" id="answer-btn">
                </form>

                <?php if ($loginUser && (int) $question['userId'] === (int) $loginUser['id'] && empty($answers)): ?>
                    <form action="questionEdit.php" method="GET">
                        <input type="hidden" name="questionId"
                            value="<?= htmlspecialchars((string) $question['id'], ENT_QUOTES, 'UTF-8') ?>">
                        <input type="submit" value="編集" class="edit-btn">
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <div class="answer-container">
            <h2 id="answer-h2">Answer</h2>
            <?php if (!empty($pagedAnswers)): ?>
                <?php foreach ($pagedAnswers as $ans): ?>
                    <?php $ans['name'] = $ans['answererName'] ?? ''; ?>
                    <div class="question-container">
                        <div class="question-name"><?= htmlspecialchars((string) $ans['name'], ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="question-time">
                            <?= date('Y年m月d日 H時i分', strtotime($ans['date'])) ?>
                        </div>
                        <div class="question-text">
                            <?= nl2br(htmlspecialchars((string) $ans['answer'], ENT_QUOTES, 'UTF-8')) ?>
                        </div>

                        <?php if ($loginUser && (int) $ans['userId'] === (int) $loginUser['id']): ?>
                            <div class="question-container-btn">
                                <form action="answerEdit.php" method="GET">
                                    <input type="hidden" name="answerId"
                                        value="<?= htmlspecialchars((string) $ans['id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="questionId"
                                        value="<?= htmlspecialchars((string) $question['id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="submit" value="編集" class="edit-btn">
                                </form>

                                <form
                                    action="detail.php?questionId=<?= htmlspecialchars((string) $question['id'], ENT_QUOTES, 'UTF-8') ?>"
                                    method="POST">
                                    <input type="hidden" value="<?= htmlspecialchars((string) $ans['id'], ENT_QUOTES, 'UTF-8') ?>"
                                        name="answerId">
                                    <input type="submit" value="削除" name="delete" class="delete-btn">
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>まだ回答はありません。</p>
            <?php endif; ?>
        </div>

        <?php renderAnswerPagination((int) $questionId, (int) $num, (int) $page); ?>
    </main>

    <footer>
        <form action="questions.php" method="GET">
            <input type="hidden" name="page"
                value="<?= htmlspecialchars((string) $questionPage, ENT_QUOTES, 'UTF-8') ?>">
            <?php if ($keyword !== ''): ?>
                <input type="hidden" name="keyword" value="<?= htmlspecialchars((string) $keyword, ENT_QUOTES, 'UTF-8') ?>">
            <?php endif; ?>
            <button type="submit" class="return-btn">戻る</button>
        </form>
        <div>@アイデア倉庫</div>
    </footer>
</body>

</html>