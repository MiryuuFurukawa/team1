<?php
    require_once 'function.php'
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>質問一覧画面</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <!-- ここらへんにエラーメッセージ表示させたい -->
    <!-- ヘッダー -->
        <h1>
            <a href="questions.php">アイデア倉庫</a>
        </h1>
    <form action="questionInput.php" method="GET">
        <input type="submit" value="質問">
    </form>
    <form action="index.php" method="POST">
        <input type="submit" name="logout" value="ログアウト">
    </form>
</header>
<main>
<!--以下質問一覧画面 -->
<div class="queston-container">
    <h2>質問一覧</h2>
            <div>質問ID：</div>
            <div>投稿者：</div>
            <div>質問内容：</div>
            <div>投稿日時：</div>
        <form action="">
            <input type="submit" value="詳細">
        </form>
        <form action="questions.php" name="delete" method="POST">
            <input type="hidden" name="questionId">
            <input type="submit" value="削除">
        </form>
</div>
</main>
<footer>
    <div>@アイデア倉庫</div>
</footer>
</body>
</html>