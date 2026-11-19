<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>回答編集画面</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- ここから本文 -->
    <header>
        <!-- <form action="questions.php" method="GET"> -->
        <h1><a href="questions.php">アイデア倉庫</a></h1>
        <!-- </form> -->
        <div class="header-btn">
            <form action="index.php" classmethod="POST">
                <input type="submit" name="logout" value="ログアウト">
            </form>
        </div>
    </header>

    <!-- 編集画面 -->
    <main>
        <div class="question-container">
            <form action="questionEdit.php" method="POST">
                <div class="edit-form">
                    <div><label>回答を編集する</label></div>
                    <textarea name="question"></textarea>
                </div>
                <input type="hidden" name="questionId">
                <div class="question-container-btn">
                    <input type="submit" value="編集">
            </form>


        </div>
        </div>

        <form action="detail.php" method="GET">
            <input type="submit" value="戻る">
        </form>
    </main>
    <footer>
        <div>@アイデア倉庫</div>
    </footer>
</body>

</html>