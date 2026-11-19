<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>利用者登録画面</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <h1><a href="questions.php">アイデア倉庫</a></h1>
    </header>
    <main>
        <!-- 表示名、ユーザーID、パスワードを登録 -->
        <h2>利用者登録</h2>
        <form action="userAdd.php" method="post" class="index-main">
            <div><label for="viewName">表示名　　　</label>
                <input type="text" name="viewName">
            </div>
            <div>
                <label for="userId">ユーザーID　</label>
                <input type="text" name="userId">
            </div>
            <div>
                <label for="userPw">パスワード　</label>
                <input type="password" name="userPw">
            </div>
            <div>
                <input type="submit" value="登録" class="login-btn">
            </div>
        </form>
    </main>
    <footer>
        <form action="index.php" method="get">
            <button type="submit" class="return-btn">戻る</button>
        </form>
        <div>@アイデア倉庫</div>
    </footer>
</body>

</html>