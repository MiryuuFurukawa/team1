<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>利用者登録画面</title>
</head>
<body>
    
   <header>
        <form>
            <h1><a href="questions.php">アイデア倉庫</a></h1>
        </form>
    </header>

        <!-- 表示名、ユーザーID、パスワードを登録 -->
    <form action="userAdd.php" method="post">
        利用者登録</br>
        <div>
            <label for="viewName">表示名</label>
            <input type="text" name="viewName" >
        </div>
        <div>
            <label for="userId">ユーザーID</label>
            <input type="text" name="userId" >
        </div>
        <div>
            <label for="userPw">パスワード</label>
            <input type="password" name="userPw" >
        </div>
        <div>
            <input type="submit" value="登録">
        </div>
    </form>
        <form action="index.php" method="get">
        <button type="submit">戻る</button>
    </form>
</body>
</html>