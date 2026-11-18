<?php
session_start();
// redirect処理は関数？
if (isset($_SESSION['userId'])) {
    header('Location: questions.php');
    exit;
}
// functions.phpをつなげる
require_once 'function.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'] ?? '';
    $userPw = $_POST['userPw'] ?? '';

    if (isUser($userId, $userPw)) {
        $_SESSION['userId'] = $userId;
        header('Location: questions.php');
        exit;
    } else {
        $error = 'ユーザーIDまたはパスワードが違います。';
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン画面</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<!-- ヘッダー -->
    <header>
        <form>
            <h1><a href="questions.php">アイデア倉庫</a></h1>
        </form>
    </header>

    <!-- ユーザーIDとパスワードでログイン -->
    <form action="index.php" method="post">
        <div>
            <label for="userId">ユーザーID</label>
            <input type="text" name="userId">
        </div>
        <div>
            <label for="userPw">パスワード</label>
            <input type="password" name="userPw">
        </div>
        <div>
            <input type="submit" value="ログイン">
        </div>
    </form>
    <!-- 新規登録ページへ移動リンク -->
    <form>
        <a href="userAdd.php">新規登録</a>
    </form>
<!-- フッター -->
    <footer>
        <div>@アイデア倉庫</div>
    </footer>
</body>

</html>