<?php
require_once 'function.php';
startSessionIfNeeded();

$err = '';
$userId = '';
$page = $_GET['page'] ?? ($_POST['page'] ?? '');
$questionId = $_GET['questionId'] ?? ($_POST['questionId'] ?? '');

 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ログアウトボタンが押された場合の処理を追加
    if (isset($_POST['logout'])) {
        logoutProcess();
    }
    if(isset($_POST['userId']) || isset($_POST['userPw'])){
    // 通常のログイン処理
    $userId = $_POST['userId'] ?? '';
    $userPw = $_POST['userPw'] ?? '';
    $err = loginProcess($userId, $userPw, $page, $questionId);
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

    <header>
        <h1><a href="questions.php">アイデア倉庫</a></h1>
        <div class="header-btn">
            <form action="questions.php" method="GET">
                <input type="submit" id="current-btn" value="質問一覧">
            </form>
            <form action="questionInput.php" method="GET">
                <input type="submit" id="other-btn" value="質問する">
            </form>
        </div>
    </header>

    <main>
        <h2>ログイン</h2>

        <?php if ($err !== ''): ?>
            <p style="color:red;"><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <div class="index-main">
            <form action="index.php" method="post">
                <input type="hidden" name="page" value="<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="questionId"
                    value="<?= htmlspecialchars($questionId, ENT_QUOTES, 'UTF-8') ?>">

                <div>
                    <label for="userId">ユーザーID　</label>
                    <input type="text" id="userId" name="userId"
                        value="<?= htmlspecialchars($userId, ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div>
                    <label for="userPw">パスワード　</label>
                    <input type="password" id="userPw" name="userPw">
                </div>
                <div id="login-btn">
                    <input type="submit" value="ログイン">
                </div>
            </form>

            <div class="userAdd">
                <a href="userAdd.php">新規登録</a>
            </div>
        </div>
    </main>
    <footer>
        <div>@アイデア倉庫</div>
    </footer>

</body>

</html>