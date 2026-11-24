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
$keyword = $_GET['keyword'] ?? '';
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
            <form action="questions.php" method="GET">
                <input type="submit" id="current-btn" value="質問一覧">
            </form>
            <form action="questionInput.php" method="GET">
                <input type="submit" id="other-btn" value="質問する">
            </form>
            <?php if (isLoggedIn()): ?>
                <form action="index.php" method="POST">
                    <input type="submit" id="logout-btn" name="logout" value="ログアウト">
                </form>
            <?php endif; ?>
            <?php if (!isLoggedIn()): ?>
                <form action="index.php" method="POST">
                    <input type="submit" id="other-btn" name="login" value="ログイン">
                </form>
            <?php endif; ?>
        </div>
    </header>

    <main>
        <form action="questions.php" method="GET">
            <input type="text" name="keyword" value="<?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') ?>"
                maxlength="20">
            <input type="submit" value="検索">
            <?php if ($keyword !== ''): ?>
                <a href="questions.php">検索解除</a>
            <?php endif; ?>
        </form>

        <?php if ($questions = getQuestionsByKeyword($keyword)): ?>
            <?php foreach ($questions as $n => $q): ?>
                <div class="question-container">
                    <form><input type="hidden" name="questionId"
                            value="<?= htmlspecialchars($questions[$n]['id'], ENT_QUOTES, 'UTF-8') ?>"></form>
                    <div class="question-name"><?= htmlspecialchars($questions[$n]['name'], ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="question-time"><?= htmlspecialchars($questions[$n]['date'], ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="question-text"><?= nl2br(htmlspecialchars($questions[$n]['question'], ENT_QUOTES, 'UTF-8')) ?>
                    </div>

                    <div class="question-container-btn">
                        <form action="detail.php" method="GET">
                            <input type="hidden" name="questionId"
                                value="<?= htmlspecialchars($questions[$n]['id'], ENT_QUOTES, 'UTF-8') ?>">
                            <input type="submit" id="detail-btn" value="詳細">
                        </form>

                        <?php if ($loginUser && (int) $loginUser['id'] === (int) $questions[$n]['userId']): ?>
                            <form action="questions.php" method="POST">
                                <input type="hidden" name="questionId"
                                    value="<?= htmlspecialchars($questions[$n]['id'], ENT_QUOTES, 'UTF-8') ?>">
                                <input type="submit" id="delete-btn" value="削除">
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>該当する質問は見つかりませんでした。</p>
        <?php endif; ?>
    </main>
    <footer>
        <div>@アイデア倉庫</div>
    </footer>
</body>

</html>