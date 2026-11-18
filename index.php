<?php
require_once 'function.php';
startSessionIfNeeded();

$err = '';
$userId = '';
$from = $_GET['from'] ?? ($_POST['from'] ?? '');
$questionId = $_GET['questionId'] ?? ($_POST['questionId'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['logout'])) {
        // ログアウト処理（画面遷移図：ログアウト時は質問一覧へ遷移）
        logoutProcess();
    } else {
        // ログイン処理
        $userId = $_POST['userID'] ?? '';
        $userPw = $_POST['userPw'] ?? '';
        $err = loginProcess($userId, $userPw, $from, $questionId);
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