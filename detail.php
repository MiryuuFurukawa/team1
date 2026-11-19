<?php
require_once 'function.php';

$questionId = $_GET['questionId'] ?? '';

if ($questionId === '' || !ctype_digit((string) $questionId)) {
    header('Location: questions.php');
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
                <form action="index.php" method="POST">
                    <input type="submit" value="ログアウト" name="logout">
                </form>
            </div>
        <?php endif; ?>
    </header>

    <main>
        <form action="answer.php" method="GET">
            <h2>質問</h2>
            <div class="question-container">
                <div>質問者：<?= htmlspecialchars((string) $question['name'], ENT_QUOTES, 'UTF-8') ?></div>
                <div>質問ID：<?= htmlspecialchars((string) $question['id'], ENT_QUOTES, 'UTF-8') ?></div>
                <div>質問内容：<?= nl2br(htmlspecialchars((string) $question['question'], ENT_QUOTES, 'UTF-8')) ?></div>
                <div>投稿日時：<?= htmlspecialchars((string) $question['date'], ENT_QUOTES, 'UTF-8') ?></div>

                <input type="hidden" name="questionId"
                    value="<?= htmlspecialchars((string) $question['id'], ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="page" value="answer">

                <input type="submit" value="回答" name="answer">
            </div>
        </form>

        <div class="answer-container">
            <h2>回答</h2>
            <?php if (!empty($answers)): ?>
                <?php foreach ($answers as $ans): ?>
                    <?php
                    $ans['name'] = $ans['answererName'] ?? '';
                    ?>
                    <div class="question-container">
                        <div>回答者：<?= htmlspecialchars((string) $ans['name'], ENT_QUOTES, 'UTF-8') ?></div>
                        <div>回答内容：<?= nl2br(htmlspecialchars((string) $ans['answer'], ENT_QUOTES, 'UTF-8')) ?></div>
                        <div>回答投稿日時：<?= htmlspecialchars((string) $ans['date'], ENT_QUOTES, 'UTF-8') ?></div>

                        <?php if (isLoggedIn() && (int) $ans['userId'] === (int) $_SESSION['user']['id']): ?>
                            <form
                                action="detail.php?questionId=<?= htmlspecialchars((string) $question['id'], ENT_QUOTES, 'UTF-8') ?>"
                                method="POST">
                                <input type="hidden" value="<?= htmlspecialchars((string) $ans['id'], ENT_QUOTES, 'UTF-8') ?>"
                                    name="answerId">
                                <input type="submit" value="削除" name="delete">
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>まだ回答はありません。</p>
            <?php endif; ?>
        </div>

        <form action="questions.php" method="GET">
            <input type="submit" value="戻る">
        </form>
    </main>
    <footer>
        <div>@アイデア倉庫</div>
    </footer>
</body>

</html>