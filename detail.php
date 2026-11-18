<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>質問詳細画面</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1><a href="questions.php">アイデア倉庫</a></h1>
        <!-- ログアウトボタン -->
        <div class="header-btn">
            <form action="index.php" method="POST">
                <input type="submit" value="ログアウト" name="logout">
            </form>
        </div>
    </header>


    <!-- 質問内容表示-->
    <main>
        <form action="answer.php" method="GET">
            <h2>質問</h2>
            <div class="question-container">

                <div>質問者：</div>
                <div>質問ID：</div>
                <div>質問内容：</div>
                <div>投稿日時：</div>
                <input type="hidden" value="質問ID" name="questionId">
                <input type="submit" value="回答" name="answer">
            </div>
        </form>

        <!-- 回答内容表示 -->
        <div class="answer-container">
            <h2>回答</h2>
            <div class="question-container">
                <div>回答者：</div>
                <div>回答内容：</div>
                <div>回答投稿日時：</div>
                <!-- 回答ID　回答削除ボタン -->
                <form action="detail.php" method="POST">
                    <input type="hidden" value="回答ID" name="answerId">
                    <input type="submit" value="削除" name="delete">
                </form>
            </div>
        </div>

        <!-- 戻るボタン -->
        <form action="questions.php" method="GET">
            <input type="submit" value="戻る">
        </form>
    </main>
    <footer>
        <div>@アイデア倉庫</div>
    </footer>
</body>

</html>