<?php
require_once 'function.php';

// ログインしていなければログイン画面へリダイレクト
requireLogin();

$error = '';
$questionId = '';
$questionText = '';

// 1. GETリクエスト時（詳細画面の「編集」ボタンから飛んできた場合）
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $questionId = $_GET['questionId'] ?? '';

    // IDのバリデーション
    if ($questionId === '' || !ctype_digit((string) $questionId)) {
        header('Location: questions.php');
        exit;
    }

    // 該当する質問情報を取得
    $question = getQuestionById((int) $questionId);
    if ($question === false) {
        header('Location: questions.php');
        exit;
    }

    // ログインユーザーが質問者本人かチェック（不正アクセス防止）
    if ((int) $question['userId'] !== (int) $_SESSION['user']['id']) {
        header('Location: questions.php');
        exit;
    }

    // テキストエリアに初期表示する内容
    $questionText = $question['question'];
}

// 2. POSTリクエスト時（編集画面の「編集」ボタンが押されて送信された場合）
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $questionId = $_POST['questionId'] ?? '';
    $questionText = $_POST['question'] ?? '';

    // IDのバリデーション
    if ($questionId === '' || !ctype_digit((string) $questionId)) {
        header('Location: questions.php');
        exit;
    }

    // 再度、本人確認のためDBから質問を取得
    $question = getQuestionById((int) $questionId);
    if ($question === false || (int) $question['userId'] !== (int) $_SESSION['user']['id']) {
        header('Location: questions.php');
        exit;
    }

    // 入力チェック
    if (trim($questionText) === '') {
        $error = '質問内容を入力してください。';
    } elseif (mb_strlen($questionText) > 256) {
        // database.sqlの varchar(256) 制約に合わせる場合
        $error = '質問内容は256文字以内で入力してください。';
    } else {
        // DBを更新 (function.php の updateQuestion を利用)
        if (updateQuestion((int) $questionId, $questionText)) {
            // 更新成功時は詳細画面へリダイレクト
            header('Location: detail.php?questionId=' . urlencode($questionId));
            exit;
        } else {
            $error = '更新に失敗しました。もう一度お試しください。';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>質問編集画面</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1><a href="questions.php">アイデア倉庫</a></h1>
        <?php if (isLoggedIn()): ?>
            <div class="header-btn">
                <form action="index.php" method="POST">
                    <input type="submit" name="logout" value="ログアウト">
                </form>
            </div>
        <?php endif; ?>
    </header>

    <main>
        <div class="question-container">
            <?php if (!empty($error)): ?>
                <p style="color: red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>

            <form action="questionEdit.php" method="POST">
                <div class="edit-form">
                    <div><label>質問を編集する</label></div>
                    <textarea name="question"><?= htmlspecialchars($questionText, ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
                <!-- どの質問を編集しているか保持するための隠しフィールド -->
                <input type="hidden" name="questionId"
                    value="<?= htmlspecialchars((string) $questionId, ENT_QUOTES, 'UTF-8') ?>">

                <div class="question-container-btn">
                    <input type="submit" value="編集する">
                </div>
            </form>
        </div>

        <!-- 戻るボタン（どの質問の詳細に戻るかをGETパラメータで指定） -->
        <form action="detail.php" method="GET">
            <input type="hidden" name="questionId"
                value="<?= htmlspecialchars((string) $questionId, ENT_QUOTES, 'UTF-8') ?>">
            <input type="submit" value="戻る">
        </form>
    </main>
    <footer>
        <div>@アイデア倉庫</div>
    </footer>
</body>

</html>