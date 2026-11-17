<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
//データの受け取り
$question = $_GET['question'];

//必須項目のチェック
if ($question == ''){
    header("Location: questionInput.php"); //空の時questionInput.phpへ移動
    exit();
}

//DBに接続
$dsn = 'mysql:host=localhost;dbname=ideastock;charset=utf8';

?>