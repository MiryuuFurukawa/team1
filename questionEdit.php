<?php
require_once 'function.php';

requireLogin();

$error = '';
$questionId = '';
$questionText = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
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

    if ((int) $question['userId'] !== (int) $_SESSION['user']['id']) {
        header('Location: questions.php');
        exit;
    }

    $questionText = $question['question'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $questionId = $_POST['questionId'] ?? '';
    $questionText = $_POST['question'] ?? '';

    if ($questionId === '' || !ctype_digit((string) $questionId)) {
        header('Location: questions.php');
        exit;
    }

    $question = getQuestionById((int) $questionId);
    if ($question === false || (int) $question['userId'] !== (int) $_SESSION['user']['id']) {
        header('Location: questions.php');
        exit;
    }

    if (trim($questionText) === '') {
        $error = '質問内容を入力してください。';
    } elseif (mb_strlen($questionText, 'UTF-8') > 256) {
        $error = '質問内容は256文字以内で入力してください。';
    } else {
        if (updateQuestion((int) $questionId, $questionText)) {
            header('Location: detail.php?questionId=' . urlencode($questionId));
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
    <title>質問編集画面</title>
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
        <form action="questionEdit.php" method="POST">
            <div class="edit-form">
                <h2 id="question-h2">質問を編集する</h2>
                <textarea name="question"
                    maxlength="256"><?= htmlspecialchars($questionText, ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
            <input type="hidden" name="questionId"
                value="<?= htmlspecialchars((string) $questionId, ENT_QUOTES, 'UTF-8') ?>">
        <?php if (!empty($error)): ?>
            <span style="color: red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></span>
        <?php endif; ?>
            <input type="submit" value="編集" id="entry-btn">
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