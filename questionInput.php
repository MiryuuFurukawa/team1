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
        <form>
        <h1><a href="question.php">アイデア倉庫</a></h1><br>
        </form>
        
        <form>
            <input type="submit" value="ログアウト">
        </form>
    </header>

    <!-- 投稿画面 -->
    <form action="post.php" method="post">
        <div>
            <label>質問を投稿する</label><br>
            <textarea name="question" rows="5" cols="40"></textarea>
        </div>

        <input type="submit" value="登録"><br>
    </form>

    <form>
        <input type="submit" value="戻る"><br>
    </form>
</body>

</html>