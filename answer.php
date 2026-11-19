<?php
// DB接続や共通関数の読み込み
require_once 'function.php';


$questionId = $_GET['questionId'] ?? $_POST['questionId'] ?? '';
requireLogin('answer', (string) $questionId);

// 質問IDが空、または数字以外の不正な値なら一覧画面へ戻す
if ($questionId === '' || !ctype_digit((string) $questionId)) {
    header('Location: questions.php');
    exit;
}
// 対象となる質問データを取得（存在しなければ一覧画面へ戻す）
$question = getQuestionById((int) $questionId);
if ($question === false) {
    header('Location: questions.php');
    exit;
}

// 定義書の $question['name'] に合わせる[cite: 2]
$question['name'] = $question['userName'];

$err = '';
$answer = '';

// 回答登録フォーム送信時（POST）の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answer = trim($_POST['answer'] ?? '');

    if ($answer === '') {
        $err = '回答を入力してください。';
        
        // 登録成功時は質問詳細画面へ遷移
    } elseif (addAnswer((int) $_SESSION['user']['id'], (int) $question['id'], $answer)) {
        header('Location: detail.php?questionId=' . urlencode((string) $question['id']));
        exit;
    } else {
        $err = '回答の登録に失敗しました。';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>回答入力画面</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1><a href="questions.php">アイデア倉庫</a></h1>
        <?php if (isLoggedIn()): ?>
            <form action="index.php" method="POST">
                <input type="submit" name="logout" value="ログアウト">
            </form>
        <?php endif; ?>
    </header>

    <section>
        <p>質問者：<?= htmlspecialchars((string) $question['name'], ENT_QUOTES, 'UTF-8') ?></p>
        <p>質問：<?= nl2br(htmlspecialchars((string) $question['question'], ENT_QUOTES, 'UTF-8')) ?></p>
        <p>質問投稿日：<?= htmlspecialchars((string) $question['date'], ENT_QUOTES, 'UTF-8') ?></p>
    </section>

    <form action="answer.php" method="POST">
        <input type="hidden" name="questionId"
            value="<?= htmlspecialchars((string) $question['id'], ENT_QUOTES, 'UTF-8') ?>">
        <div>
            <label for="answer">回答</label><br>
            <textarea id="answer" name="answer" rows="5"
                cols="40"><?= htmlspecialchars($answer, ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>
        <?php if ($err !== ''): ?>
            <p style="color: red;"><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <input type="submit" value="登録">
    </form>

    <form action="detail.php" method="GET">
        <input type="hidden" name="questionId"
            value="<?= htmlspecialchars((string) $question['id'], ENT_QUOTES, 'UTF-8') ?>">
        <input type="submit" value="戻る">
    </form>
</body>

</html>