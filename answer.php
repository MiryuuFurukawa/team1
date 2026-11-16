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
            <h1><a href="question.php">アイデア倉庫</a><input type="submit" value="ログアウト"></h1><br>
        </form>
    </header>

    <!-- 質問-->
     <div>
            <label>質問</label>
        <!-- question.phpからもてくる -->
    
    <!-- 回答 -->
        <div>
            <label>回答</label>
            <textarea name="answer" value="空白" rows="5" cols="40"></textarea>
        </div>
        <input type="submit" value="登録"><br>
        <input type="submit" value="戻る"><br>

    </form>
</body>

</html>