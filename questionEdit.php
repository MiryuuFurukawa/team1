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
    } elseif (mb_strlen($questionText) > 256) {
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

            <form action="questionEdit.php" method="POST">
                <div class="edit-form">
                    <div><label>質問を編集する</label></div>
                    <textarea name="question"><?= htmlspecialchars($questionText, ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
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