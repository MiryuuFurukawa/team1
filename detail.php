<?php
require_once 'function.php';

$questionId = $_GET['questionId'] ?? '';

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

    header('Location: detail.php?questionId=' . urlencode($questionId));
    exit;
}

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
        <?php if (isLoggedIn()): ?>
            <div class="header-btn">
                <form action="index.php" method="POST" id="logout-btn">
                    <input type="submit" value="ログアウト" name="logout">
                </form>
            </div>
        <?php endif; ?>
    </header>

    <main>
        <h2 id="question-h2">Question</h2>
        <div class="question-container">
            <form action="answer.php" method="GET">
                <input type="hidden" value="<?= htmlspecialchars((string) $question['id'], ENT_QUOTES, 'UTF-8') ?>">
                <div class="question-name"><?= htmlspecialchars((string) $question['name'], ENT_QUOTES, 'UTF-8') ?>
                </div>
                <div class="question-time"><?= htmlspecialchars((string) $question['date'], ENT_QUOTES, 'UTF-8') ?>
                </div>
                <div class="question-text">
                    <?= nl2br(htmlspecialchars((string) $question['question'], ENT_QUOTES, 'UTF-8')) ?>
                </div>
            </form>
            <div class="question-container-btn">
                <form action="answer.php" method="GET">
                    <input type="hidden" name="questionId"
                        value="<?= htmlspecialchars((string) $question['id'], ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="page" value="answer">
                    <input type="submit" value="回答" name="answer" id="answer-btn">
                </form>
                <?php if (isLoggedIn() && (int) $question['userId'] === (int) $_SESSION['user']['id'] && empty($answers)): ?>
                    <form action="questionEdit.php" method="GET">
                        <input type="hidden" name="questionId"
                            value="<?= htmlspecialchars((string) $question['id'], ENT_QUOTES, 'UTF-8') ?>">
                        <input type="submit" value="編集" id="edit-btn">
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <div class="answer-container">
            <h2 id="answer-h2">Answer</h2>
            <?php if (!empty($answers)): ?>
                <?php foreach ($answers as $ans): ?>
                    <?php
                    $ans['name'] = $ans['answererName'] ?? '';
                    ?>
                    <div class="question-container">
                        <div class="question-name"><?= htmlspecialchars((string) $ans['name'], ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="question-time"><?= htmlspecialchars((string) $ans['date'], ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="question-text">
                            <?= nl2br(htmlspecialchars((string) $ans['answer'], ENT_QUOTES, 'UTF-8')) ?>
                        </div>

                        <div class="question-container-btn">
                            <?php if (isLoggedIn() && (int) $ans['userId'] === (int) $_SESSION['user']['id']): ?>
                                <form action="answerEdit.php" method="GET">
                                    <input type="hidden" name="answerId"
                                        value="<?= htmlspecialchars((string) $ans['id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="questionId"
                                        value="<?= htmlspecialchars((string) $question['id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="submit" value="編集" id="edit-btn">
                                </form>

                            <?php endif; ?>
                            <?php if (isLoggedIn() && (int) $ans['userId'] === (int) $_SESSION['user']['id']): ?>
                                <form
                                    action="detail.php?questionId=<?= htmlspecialchars((string) $question['id'], ENT_QUOTES, 'UTF-8') ?>"
                                    method="POST">
                                    <input type="hidden" value="<?= htmlspecialchars((string) $ans['id'], ENT_QUOTES, 'UTF-8') ?>"
                                        name="answerId">
                                    <input type="submit" value="削除" name="delete" id="delete-btn">
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>まだ回答はありません。</p>
                <?php endif; ?>
            </div>
        </div>
        </div>
    </main>
    <footer>
        <form action="questions.php" method="GET">
            <button type="submit" class="return-btn">戻る</button>
        </form>
        <div>@アイデア倉庫</div>
    </footer>
</body>

</html>