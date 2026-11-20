<?php
require_once 'function.php';

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

    if (trim($answerText) === '') {
        $error = '回答内容を入力してください。';
    } elseif (mb_strlen($answerText) > 256) {
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
        <?php if (isLoggedIn()): ?>
            <div class="header-btn">
                <form action="index.php" method="POST">
                    <input type="submit" name="logout" value="ログアウト">
                </form>
            </div>
        <?php endif; ?>
    </header>

    <main>
        <div class="question-container">
            <?php if (!empty($error)): ?>
                <p style="color: red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>

            <form action="answerEdit.php" method="POST">
                <div class="edit-form">
                    <div><label>回答を編集する</label></div>
                    <textarea name="answer"><?= htmlspecialchars($answerText, ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
                <input type="hidden" name="answerId"
                    value="<?= htmlspecialchars((string) $answerId, ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="questionId"
                    value="<?= htmlspecialchars((string) $questionId, ENT_QUOTES, 'UTF-8') ?>">

                <div class="question-container-btn">
                    <input type="submit" value="編集する">
                </div>
            </form>
        </div>

        <form action="detail.php" method="GET">
            <input type="hidden" name="questionId"
                value="<?= htmlspecialchars((string) $questionId, ENT_QUOTES, 'UTF-8') ?>">
            <input type="submit" value="戻る">
        </form>
    </main>
    <footer>
        <div>@アイデア倉庫</div>
    </footer>
</body>

</html>