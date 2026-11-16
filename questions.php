<!DOCTYPE html>
<html lang="en">
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
    <form action="questions.php" method="GET">
        <h1>
            <a href="questions.php">アイデア倉庫</a>
        </h1>
    </form>
    <form action="questionInput.php" method="GET">
        <input type="submit" value="質問">
    </form>
    <form action="index.php" method="POST">
        <input type="submit" name="logout" value="ログアウト">
    </form>
</header>
<!--以下質問一覧画面 -->
    <form action="detail.php"method="GET">
        <div>質問ID：</div>
        <div>投稿者：</div>
        <div>質問内容：</div>
        <div>投稿日時：</div>
        <input type="submit" value="詳細">
    </form>
    <form action="question" name="delete" method="POST">
        <input type="submit" value="削除">
    </form>

</body>
</html>

<?php
?>
