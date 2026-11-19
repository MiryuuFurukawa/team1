<?php
require_once 'function.php';

startSessionIfNeeded();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['questionId'])) {
    if (isLoggedIn()) {
        $questionId = (int) $_POST['questionId'];
        deleteQuestion($questionId);
        header('Location: questions.php');
        exit;
    }
}

$loginUser = $_SESSION['user'] ?? null;

$err = $_SESSION['err'] ?? '';
unset($_SESSION['err']);
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
        <h1><a href="questions.php">アイデア倉庫</a></h1>
        <div class="header-btn">
            <form action="questionInput.php" method="GET">
                <input type="submit" class="question-btn" value="質問">
            </form>
            <?php if (isLoggedIn()): ?>
                <form action="index.php" method="POST">
                    <input type="submit" name="logout" value="ログアウト">
                </form>
            <?php endif; ?>
        </div>
    </header>

    <main>
        <?php if ($questions = getQuestion()): ?>
            <?php foreach ($questions as $n => $q): ?>
                <div class="question-container">
                    <div>質問ID：<?= htmlspecialchars($questions[$n]['id'], ENT_QUOTES, 'UTF-8') ?></div>
                    <div>投稿者：<?= htmlspecialchars($questions[$n]['name'], ENT_QUOTES, 'UTF-8') ?></div>
                    <div>質問内容：<?= htmlspecialchars($questions[$n]['question'], ENT_QUOTES, 'UTF-8') ?></div>
                    <div>投稿日時：<?= htmlspecialchars($questions[$n]['date'], ENT_QUOTES, 'UTF-8') ?></div>

                    <div class="question-container-btn">
                        <form action="detail.php" method="GET">
                            <input type="hidden" name="questionId"
                                value="<?= htmlspecialchars($questions[$n]['id'], ENT_QUOTES, 'UTF-8') ?>">
                            <input type="submit" class="detail-btn" value="詳細">
                        </form>

                        <?php if ($loginUser && (int) $loginUser['id'] === (int) $questions[$n]['userId']): ?>
                            <form action="questions.php" method="POST">
                                <input type="hidden" name="questionId"
                                    value="<?= htmlspecialchars($questions[$n]['id'], ENT_QUOTES, 'UTF-8') ?>">
                                <input type="submit" class="delete-btn" value="削除">
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>

    <footer>
        <div>@アイデア倉庫</div>
    </footer>
</body>

</html>