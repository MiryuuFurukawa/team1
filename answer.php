<?php
require_once 'function.php';

$questionId = $_GET['questionId'] ?? $_POST['questionId'] ?? '';
requireLogin('answer', (string) $questionId);

if ($questionId === '' || !ctype_digit((string) $questionId)) {
    header('Location: questions.php');
    exit;
}

$question = getQuestionById((int) $questionId);
if ($question === false) {
    header('Location: questions.php');
    exit;
}

$question['name'] = $question['userName'];

$err = '';
$answer = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answer = trim($_POST['answer'] ?? '');

    if ($answer === '') {
        $err = '回答を入力してください。';
    } elseif (mb_strlen($answer, 'UTF-8') > 256) {
        $err = '回答内容は256文字以内で入力してください。';
    } elseif (addAnswer((int) $_SESSION['user']['id'], (int) $question['id'], $answer)) {
        header('Location: detail.php?questionId=' . urlencode((string) $question['id']));
        exit;
    } else {
        $err = '回答の登録に失敗しました。';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>回答入力画面</title>
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
            <?php endif; ?>
            <?php if (!isLoggedIn()): ?>
                <form action="index.php" method="POST">
                    <input type="submit" id="other-btn" name="login" value="ログイン">
                </form>
            <?php endif; ?>
        </div>
    </header>
    <main>
        <h2 id="question-h2">Question</h2>
        <div class="question-container">
            <div class="question-name"><?= htmlspecialchars((string) $question['name'], ENT_QUOTES, 'UTF-8') ?></div>
            <div class="question-time"><?= htmlspecialchars((string) $question['date'], ENT_QUOTES, 'UTF-8') ?></div>
            <div class="question-text">


                <?= nl2br(htmlspecialchars((string) $question['question'], ENT_QUOTES, 'UTF-8')) ?>
            </div>
        </div>

        <form action="answer.php" method="POST" class="answer-container">
            <input type="hidden" name="questionId"
                value="<?= htmlspecialchars((string) $question['id'], ENT_QUOTES, 'UTF-8') ?>">
            <div class="edit-form">
                <h2 id="answer-h2"><label for="answer">Answer</label></h2>
                <textarea id="answer" name="answer"
                    maxlength="256"><?= htmlspecialchars($answer, ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>

            <?php if ($err !== ''): ?>
                <span id="error"><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></span>
            <?php endif; ?>
            <input type="submit" value="登録" id="entry-btn">
        </form>
    </main>

    <footer>
        <form action="questions.php" method="GET">
            <input type="hidden" name="questionId"
                value="<?= htmlspecialchars((string) $question['id'], ENT_QUOTES, 'UTF-8') ?>">
            <button type="submit" class="return-btn">戻る</button>
        </form>
        <div>@アイデア倉庫</div>
    </footer>
</body>

</html>