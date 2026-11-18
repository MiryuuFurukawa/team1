<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>回答画面</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- ここから本文 -->
    <header>
        <form>
            <h1>
                <a href="question.php">アイデア倉庫</a>
            </h1>
        </form>

        <form action="index.php" method="POST">
            <input type="submit" value="ログアウト">
        </form>
    </header>

    <!-- 質問-->


    <!-- question.phpからもてくる -->
    <label>質問</label>
    <form action="questions.php" method="POST">
        <!-- どの質問に対する回答可送るための質問ID -->
        <input type="hidden" name="questionId" value=""><br>
        <div>

            <!-- 回答 -->
            <label for="answer">回答</label><br>
            <textarea id="answer" name="answer" value="空白" rows="5" cols="40"></textarea>
        </div>
        <input type="submit" value="登録"><br>
    </form>

    <form action="detail.php" method="GET">
        <!-- 戻る際にも質問IDが必要 -->
        <input type="hidden" name="questionId" value="">
        <input type="submit" value="戻る"><br>
    </form>

</body>

</html>

<?php
//requier at once
require_once 'function.php';
//DBに接続
$db = Connect();

// 3. フォームがPOST送信されたときに関数を呼び出してデータを登録する
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answer = $_POST['answer'] ?? '';
    $questionId = $_POST['questionId'] ?? '';
    $userId = 1; // ログイン中のユーザーIDなど

    if ($answer !== '') {
        // --- ★ 関数を呼び出す ---
        addAnswer($userId, $questionId, $answer);

        header("Location: detail.php?id=" . $questionId);
        exit();
    }
}


//データの受け取り
//$question = $_POST['question'];

//必須項目チェック
if ($question = '') {
    header("Location: questionInput.php");//書式が違うとき questionInput.phpへ移動
    exit();
}
