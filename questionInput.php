<?php
require_once 'function.php';

startSessionIfNeeded();
// 未ログインの場合はログイン画面へリダイレクトし、ログイン後に questionInput.php に戻るようにする
requireLogin('questionInput');

$loginUser = $_SESSION['user'] ?? null;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- ここから本文 -->
    <header>

        <h1><a href="questions.php">アイデア倉庫</a></h1>

        <div class="header-btn">
            <form action="index.php" method="POST">
                <input type="submit" name="logout" value="ログアウト">
            </form>
        </div>
    </header>

    <!-- 投稿画面 -->
    <main>
        <form action="questions.php" method="POST">
            <div>
                <label>質問を投稿する</label><br>
                <textarea name="question" rows="5" cols="40"></textarea>
            </div>

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