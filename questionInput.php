<?php
// function.phpの読み込み
require_once 'function.php';

// 未ログイン時はログイン画面へリダイレクト（画面定義書に基づく）
requireLogin('questionInput');

$err = '';

// 登録ボタンが押された（POSTリクエストされた）場合の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question'])) {
    $questionText = trim($_POST['question']);

    // バリデーション（空チェック）
    if ($questionText === '') {
        $err = '質問内容を入力してください。';
    } else {
        // セッションからログイン中のユーザーIDを取得
        $userId = $_SESSION['user']['id'];

        // function.php の addQuestion 関数を使ってDBに登録
        if (addQuestion($userId, $questionText)) {
            // 登録成功時は質問一覧画面へ遷移
            header('Location: questions.php');
            exit;
        } else {
            $err = '質問の登録に失敗しました。';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

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
    <form action="questionInput.php" method="POST">
        <div>
            <label>質問を投稿する</label><br>
            <textarea name="question" rows="5"
                cols="40"><?php echo isset($_POST['question']) ? htmlspecialchars($_POST['question'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
        </div>

        <!-- エラーメッセージ表示 -->
        <?php if (!empty($err)): ?>
            <p><?php echo htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <input type="submit" value="登録"><br>
    </form>

    <form action="questions.php" method="GET">
        <input type="submit" value="戻る"><br>
    </form>
</body>

</html>