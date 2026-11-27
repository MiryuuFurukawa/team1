<?php
require_once 'function.php';

$num = 5;

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
$errorMsg = '';
if (isset($_GET['keyword'])) {
    if (trim($keyword) === '') {
        $errorMsg = '検索欄に文字を入れてください';
    }
}
$searchKeyword = ($errorMsg !== '') ? '' : $keyword;

$err = $_SESSION['err'] ?? '';
unset($_SESSION['err']);

// 現在のページ番号
$page = max(1, (int) ($_GET['page'] ?? 1));

// OFFSETを計算
$offset = ($page - 1) * $num;
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>質問一覧画面</title>
    <link rel="stylesheet" href="style.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.2/font/bootstrap-icons.min.css">
</head>

<body>
        <header class="header" id="js-header">
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
        <div class="search-container">
            <div class="search-form">
                <form action="questions.php" method="GET">
                    <input type="search" name="keyword" value="<?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') ?>"
                        maxlength="20">
                    <button type="submit"><i class="bi bi-search" aria-hidden="true"></i>検索</button>
                    <!--<?php if ($keyword !== ''): ?>
                <a href="questions.php">検索解除</a>
            <?php endif; ?> -->
                </form>
            </div>
            <div class="search-form">
                <?php if ($errorMsg !== ''): ?>
                    <span><?= htmlspecialchars($errorMsg, ENT_QUOTES, 'UTF-8') ?></span>
                <?php endif; ?>
            </div>
        </div>


        <?php if ($questions = getQuestionsByPage($searchKeyword, $num, $offset)): ?>
            <?php foreach ($questions as $n => $q): ?>
                <div class="question-container">
                    <form><input type="hidden" name="questionId"
                            value="<?= htmlspecialchars($questions[$n]['id'], ENT_QUOTES, 'UTF-8') ?>"></form>
                    <div class="question-name"><?= htmlspecialchars($questions[$n]['name'], ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="question-time"><?= date('Y年m月d日 H:i', strtotime($questions[$n]['date'])) ?></div>
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

        <?php renderPagination($num, $searchKeyword); ?>
    </main>
    <footer>
        <div>@アイデア倉庫</div>
    </footer>
</body>

</html>