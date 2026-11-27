<?php

require_once 'function.php';

startSessionIfNeeded();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'] ?? '';
    $userPw = $_POST['userPw'] ?? '';
    $new_userPw = $_POST['new_userPw'] ?? '';
    $confirm_userPw = $_POST['confirm_userPw'] ?? '';

    if (
        $userId === '' ||
        $userPw === '' ||
        $new_userPw === '' ||
        $confirm_userPw === ''
    ) {
        $error = 'すべてを入力してください。';
    } else if (mb_strlen($userId) < 8) {
        $error = 'ユーザーIDは8文字以上で入力してください。';
    } else if (
        mb_strlen($userPw) < 6 ||
        mb_strlen($new_userPw) < 6 ||
        mb_strlen($confirm_userPw) < 6
    ) {
        $error = 'パスワードは6文字以上で入力してください。';
    } else if (
        preg_match('/[^a-zA-Z0-9]/', $userId) ||
        preg_match('/[^a-zA-Z0-9]/', $userPw) ||
        preg_match('/[^a-zA-Z0-9]/', $new_userPw) ||
        preg_match('/[^a-zA-Z0-9]/', $confirm_userPw)
    ) {
        $error = 'ユーザーIDとパスワードは半角英数字で入力してください。';
    } else if ($new_userPw !== $confirm_userPw) {
        $error = '新しいパスワードと確認用が一致しません。';
    } else if (!isUser($userId, $userPw)) {
        $error = 'ユーザーIDまたは現在のパスワードが一致しません。。';
    }

    if ($error === '' && passwordChange($userId, $new_userPw)) {
        $success = header('Location: index.php');
    } else if ($error === '') {
        $error = 'パスワードの変更に失敗しました。';
    }
}

function passwordchange($userId, $new_userPw, )
{
    $result = false;
    try {
        $db = Connect();
        $stmt = $db->prepare("
            UPDATE user 
            SET password =:new_userPw 
            WHERE loginId =:userId
        ");

        $stmt->bindValue(':new_userPw', $new_userPw, PDO::PARAM_STR);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
        $stmt->execute();
        $result = true;
    } catch (PDOException $e) {
        $result = false;
    }
    return $result;
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>パスワードの変更</title>
</head>

<body>
    <header>
        <h1><a href="questions.php">アイデア倉庫</a></h1>
        <div class="header-btn">
        </div>
    </header>
    <main>
        <h2>パスワードの変更</h2>
        <link rel="stylesheet" href="style.css">
        <?php if ($error !== ''): ?>
            <p style="color:red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <form action="passwordChange.php" method="post" class="pw_arrange">
            <div>
                <label for="userId" class="userId">ユーザーID</label>
                <input type="text" id="userId" name="userId">
            </div>
            <div>
                <label for="userPw" class="userPw">パスワード</label>
                <input type="password" id="userPw" name="userPw">
            </div>
            <div>
                <label for="new_userPw" class="new_userPw">新しいパスワード</label>
                <input type="password" id="new_userPw" name="new_userPw">
            </div>
            <div>
                <label for="confirm_userPw" class="confirm_userPw">新しいパスワード（確認用）</label>
                <input type="password" id="confirm_userPw" name="confirm_userPw">
            </div>
            <div>
                <input type="submit" value="変更">
            </div>

        </form>
    </main>
    <footer>
        <div>@アイデア倉庫</div>
    </footer>

</body>

</html>