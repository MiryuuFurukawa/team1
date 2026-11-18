<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
</body>

</html>