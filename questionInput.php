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
        <form action="questions.php" method="GET">
            <h1><a href="questions.php">アイデア倉庫</a></h1><br>
        </form>

        <form action="index.php" method="POST">
            <input type="submit" value="ログアウト">
        </form>
    </header>

    <!-- 投稿画面 -->
    <form action=answer.php" method="POST">
        <div>
            <label>質問を投稿する</label><br>
            <textarea name="question" rows="5" cols="40"></textarea>
        </div>

        <input type="submit" value="登録"><br>
    </form>

    <form action="detail.php" method="GET">
        <input type="submit" value="戻る"><br>
    </form>
</body>

</html>

<?php
/*
//データの受け取り
$question = $_GET['question'];

//必須項目のチェック
if ($question == ''){
    header("Location: questionInput.php"); //空の時questionInput.phpへ移動
    exit();
}

//DBに接続
$dsn = 'mysql:host=localhost;dbname=ideastock;charset=utf8';

*/
?>