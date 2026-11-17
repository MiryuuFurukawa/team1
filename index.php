
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン画面</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <form>
            <h1><a href="questions.php">アイデア倉庫</a></h1>
        </form>
    </header>
    <!-- ユーザーIDとパスワードでログイン -->
    <form action="index.php" method="post">
        <div>
            <label for="userID">ユーザーID</label>
            <input type="text" name="userID" >
        </div>
        <div>
            <label for="userPw">パスワード</label>
            <input type="password" name="userPw" >
        </div>
        <div>
            <input type="submit" value="ログイン">
        </div>
    </form>
    <!-- 新規登録ページへ移動リンク -->
    <form>
        <a href="userAdd.php">新規登録</a>
    </form>
</body>

</html>