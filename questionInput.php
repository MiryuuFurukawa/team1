<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>質問入力画面</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- ここから本文 -->
    <header>
        <form action="questions.php" method="GET">
            <h1><a href="questions.php">アイデア倉庫</a></h1><br>
        </form>

        <form action="index.php" method="POST">
            <input type="submit" value="ログアウト">
        </form>
    </header>

    <!-- 投稿画面 -->
    <form action="questions.php" method="POST">
        <div>
            <label>質問を投稿する</label><br>
            <textarea name="question" rows="5" cols="40"></textarea>
        </div>

        <input type="submit" value="登録"><br>
    </form>

    <form action="questions.php" method="GET">
        <input type="submit" value="戻る"><br>
    </form>
</body>

</html>

<?php
//requier at once
require_once 'function.php';

//DBに接続
$db = Connect();

//$db を使って、データを登録する処理を行う
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = $_POST['question'] ?? '';

    
    if (trim($question) !== '') {
// functionから持ってきた $db を使って準備と実行を行う
        $sql = "INSERT INTO questions (question, created_at) VALUES (:question, NOW())";
        $stmt = $db->prepare($sql);
        $stmt->execute([':question' => $question]);

        header("Location: questions.php");
        exit();
    }
}
//必須項目チェック
if ($question = ''){
    header("Location: questionInput.php");//書式が違うとき questionInput.phpへ移動
    exit();
}
?>

