<?php
require_once 'function.php';

requireLogin('questionInput');

$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question'])) {
    $questionText = trim($_POST['question']);

    if ($questionText === '') {
        $err = '質問内容を入力してください。';
    } else {
        $userId = $_SESSION['user']['id'];

        if (addQuestion($userId, $questionText)) {
            header('Location: questions.php');
            exit;
        } else {
            $err = '質問の登録に失敗しました。';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>質問入力画面</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>

        <h1><a href="questions.php">アイデア倉庫</a></h1>

        <div class="header-btn">
            <form action="index.php" method="POST">
                <input type="submit" name="logout" value="ログアウト">
            </form>
        </div>
    </header>

    <main>
        <form action="questionInput.php" method="POST">
            <div>
                <label>質問を投稿する</label><br>
                <textarea name="question" rows="5"
                cols="40"><?php echo isset($_POST['question']) ? htmlspecialchars($_POST['question'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
            </div>

        <?php if (!empty($err)): ?>
            <p><?php echo htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

            <input type="submit" value="登録"><br>
        </form>

        <form action="questions.php" method="GET">
            <input type="submit" value="戻る"><br>
        </form>
    </main>
    <footer>
        <div>@アイデア倉庫</div>
    </footer>
</body>

</html>