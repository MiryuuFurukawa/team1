<?php
// function.php をつなげる
require_once 'function.php';
// セッションスタート
startSessionIfNeeded();


// すでに同じユーザーID（loginId）が使われているかチェックする関数
function isUserIdExists(string $userId): bool
{
    try {
        $db = Connect();
        $stmt = $db->prepare("SELECT COUNT(*) FROM user WHERE loginId = :userId");
        $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
        $stmt->execute();

        // 件数が1以上なら「すでに使われている（true）」を返す
        return (int) $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        // エラー時は安全のため重複扱い（true）にして登録を止める
        return true;
    }
}



// 新規登録処理
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $viewName = $_POST['viewName'];
    $userId = $_POST['userId'];
    $userPw = $_POST['userPw'];

    $db = Connect();
    $stmt = $db->prepare('SELECT * FROM user WHERE loginId = :userId');
    $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($viewName === '' || $userId === '' || $userPw === '') {
        $error = '入力してください。';
    } else if ($user !== false) {
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
        <form action="userAdd.php" method="post">

            <?php if ($error !== ''): ?>
                <p style="color: red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>

            <div>利用者登録</div>
            <div>
                <label for="viewName">表示名</label>
                <input type="text" name="viewName">
            </div>
            <div>
                <label for="userId">ユーザーID</label>
                <input type="text" name="userId">
            </div>
            <div>
                <label for="userPw">パスワード</label>
                <input type="password" name="userPw">
            </div>
            <div>
                <input type="submit" value="登録">
            </div>
        </form>
        <form action="index.php" method="get">
            <button type="submit">戻る</button>
        </form>
    </main>
    <footer>
        <div>@アイデア倉庫</div>
    </footer>
</body>

</html>