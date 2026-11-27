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
// 定義書の $question['name'] に合わせる[cite: 2]
$question['name'] = $question['userName'];

requireLogin();

$error = '';
$answerId = '';
$questionId = '';
$answerText = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $answerId = $_GET['answerId'] ?? '';
    $questionId = $_GET['questionId'] ?? '';


    if ($answerId === '' || !ctype_digit((string) $answerId)) {
        header('Location: questions.php');
        exit;
    }


    $answer = getAnswerById((int) $answerId);
    if ($answer === false) {
        header('Location: questions.php');
        exit;
    }

    if ((int) $answer['userId'] !== (int) $_SESSION['user']['id']) {
        header('Location: questions.php');
        exit;
    }

    $answerText = $answer['answer'];
    if ($questionId === '') {
        $questionId = $answer['questionId'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answerId = $_POST['answerId'] ?? '';
    $questionId = $_POST['questionId'] ?? '';
    $answerText = $_POST['answer'] ?? '';

    if ($answerId === '' || !ctype_digit((string) $answerId)) {
        header('Location: questions.php');
        exit;
    }

    $answer = getAnswerById((int) $answerId);
    if ($answer === false || (int) $answer['userId'] !== (int) $_SESSION['user']['id']) {
        header('Location: questions.php');
        exit;
    }

    if (isOnlyWhitespace($answerText)) {
        $error = '回答内容を入力してください。';
    } elseif (mb_strlen($answerText, 'UTF-8') > 256) {
        $error = '回答内容は256文字以内で入力してください。';
    } else {
        if (updateAnswer((int) $answerId, $answerText)) {
            $targetQid = $questionId !== '' ? $questionId : $answer['questionId'];
            header('Location: detail.php?questionId=' . urlencode($targetQid));
            exit;
        } else {
            $error = '更新に失敗しました。もう一度お試しください。';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>回答編集画面</title>
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
                

                            <?= nl2br(htmlspecialchars((string) $question['question'], ENT_QUOTES, 'UTF-8')) ?></div>
        </div>

        <form action="answerEdit.php" method="POST" class="answer-container">
            <div class="edit-form">
                <h2 id="answer-h2"><label for="answer">Answer Edit</label></h2>
                <textarea name="answer" id="answer"
                    maxlength="256"><?= htmlspecialchars($answerText, ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
            <input type="hidden" name="answerId"
                value="<?= htmlspecialchars((string) $answerId, ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="questionId"
                    value="<?= htmlspecialchars((string) $questionId, ENT_QUOTES, 'UTF-8') ?>">
        <?php if (!empty($error)): ?>
            <span style="color: red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></span>
        <?php endif; ?>
            <input type="submit" value="編集" id="answer-btn2">
        </form>
    </main>

    <footer>
        <form action="detail.php" method="GET">
            <input type="hidden" name="questionId"
                value="<?= htmlspecialchars((string) $questionId, ENT_QUOTES, 'UTF-8') ?>">
            <button type="submit" class="return-btn">戻る</button>
        </form>
        <div>@アイデア倉庫</div>
    </footer>
</body>

</html>