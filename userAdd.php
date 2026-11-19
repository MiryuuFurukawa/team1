<?php
// function.php をつなげる
require_once 'function.php';
// セッションスタート
startSessionIfNeeded();

$error = "";
$viewName = "";
$userId = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $viewName = trim($_POST['viewName'] ?? '');
    $userId = trim($_POST['userId'] ?? '');
    $userPw = $_POST['userPw'] ?? '';

    if ($viewName === '' || $userId === '' || $userPw === '') {
        $error = '入力してください。';
    } else if (mb_strlen($userId) < 6) {
        $error = 'ユーザーIDは6文字以上で入力してください。';
    } else if (mb_strlen($userPw) < 8) {
        $error = 'パスワードは8文字以上で入力してください。';
    } else if (isUserIdExists($userId)) { // ← function.php の関数を使用
        $error = 'このユーザーIDはすでに使用されています。';
    } else if (!addUser($viewName, $userId, $userPw)) {
        $error = '登録できませんでした。';
    } else {
        header('Location: index.php');
        exit;
    }
}
?>
<!-- ここからHTML -->
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
        <?php if ($error !== ''): ?>
                <p style="color: red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>

        <form action="userAdd.php" method="post" class="index-main">
            <div><label for="viewName">表示名　　　</label>
                <input type="text" name="viewName" value="<?= htmlspecialchars($viewName, ENT_QUOTES, 'UTF-8') ?>">
        <form action="userAdd.php" method="post">

            

            
            <div>
                <label for="userId">ユーザーID　</label>
                <input type="text" name="userId" value="<?= htmlspecialchars($userId, ENT_QUOTES, 'UTF-8') ?>">
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