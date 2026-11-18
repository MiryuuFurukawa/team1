<?php
require_once 'function.php';
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>質問一覧画面</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <!-- ここらへんにエラーメッセージ表示させたい -->
        <!-- ヘッダー -->
        <h1>
            <a href="questions.php">アイデア倉庫</a>
        </h1>
        <div class="header-btn">
            <form action="questionInput.php" method="GET">
                <input type="submit" class="question-btn" value="質問">
            </form>
            <form action="index.php" method="POST">
                <input type="submit" name="logout" value="ログアウト">
            </form>
        </div>

    </header>
    <main>
        <!--以下質問一覧画面 -->
        <?php
        // 質問一覧を取得
        $questions = getQuestion();
        foreach ($questions as $question): ?>
            <div class="queston-container">
                <div>質問ID：<?= htmlspecialchars($question['id']) ?></div>
                <div>投稿者：<?= htmlspecialchars(getUser($question['userId'], $userPw)) ?></div>
                <div>質問内容：<?= htmlspecialchars($question['question']) ?></div>
                <div>投稿日時：<?= htmlspecialchars($question['date']) ?></div>
                <div class="queston-container-btn">
                    <form action="detail.php" name="detail" method="GET">
                        <input type="hidden" name="questionId">
                        <input type="submit" class="detail-btn" value="詳細">
                    </form>
                    <form action="questions.php" name="delete" method="POST">
                        <input type="hidden" name="questionId">
                        <input type="submit" class="delete-btn" value="削除">
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </main>
    <footer>
        <div>@アイデア倉庫</div>
    </footer>
</body>

</html>