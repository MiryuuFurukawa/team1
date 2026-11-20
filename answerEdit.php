<?php
require_once 'function.php';

// ログインしていなければログイン画面へリダイレクト
requireLogin();

$error = '';
$answerId = '';
$questionId = '';
$answerText = '';

// 1. GETリクエスト時（詳細画面の「編集」ボタンから飛んできた場合）
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $answerId = $_GET['answerId'] ?? '';
    $questionId = $_GET['questionId'] ?? '';

    // IDのバリデーション
    if ($answerId === '' || !ctype_digit((string) $answerId)) {
        header('Location: questions.php');
        exit;
    }

    // 該当する回答情報を取得
    $answer = getAnswerById((int) $answerId);
    if ($answer === false) {
        header('Location: questions.php');
        exit;
    }

    // ログインユーザーが回答者本人かチェック（不正アクセス防止）
    if ((int) $answer['userId'] !== (int) $_SESSION['user']['id']) {
        header('Location: questions.php');
        exit;
    }

    $answerText = $answer['answer'];
    if ($questionId === '') {
        $questionId = $answer['questionId'];
    }
}

// 2. POSTリクエスト時（編集画面の「編集」ボタンが押されて送信された場合）
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answerId = $_POST['answerId'] ?? '';
    $questionId = $_POST['questionId'] ?? '';
    $answerText = $_POST['answer'] ?? '';

    // IDのバリデーション
    if ($answerId === '' || !ctype_digit((string) $answerId)) {
        header('Location: questions.php');
        exit;
    }

    // 本人確認のため再度DBから取得
    $answer = getAnswerById((int) $answerId);
    if ($answer === false || (int) $answer['userId'] !== (int) $_SESSION['user']['id']) {
        header('Location: questions.php');
        exit;
    }

    // 入力チェック
    if (trim($answerText) === '') {
        $error = '回答内容を入力してください。';
    } elseif (mb_strlen($answerText) > 256) {
        $error = '回答内容は256文字以内で入力してください。';
    } else {
        // DBを更新
        if (updateAnswer((int) $answerId, $answerText)) {
            // 更新成功時は元の質問詳細画面へリダイレクト
            $targetQid = $questionId !== '' ? $questionId : $answer['questionId'];
            header('Location: detail.php?questionId=' . urlencode($targetQid));
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
    <title>回答編集画面</title>
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

            <form action="answerEdit.php" method="POST">
                <div class="edit-form">
                    <div><label>回答を編集する</label></div>
                    <textarea name="answer"><?= htmlspecialchars($answerText, ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
                <!-- どの回答か、どの質問に戻るかを保持するための隠しフィールド -->
                <input type="hidden" name="answerId"
                    value="<?= htmlspecialchars((string) $answerId, ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="questionId"
                    value="<?= htmlspecialchars((string) $questionId, ENT_QUOTES, 'UTF-8') ?>">

                <div class="question-container-btn">
                    <input type="submit" value="編集する">
                </div>
            </form>
        </div>

        <!-- 戻るボタン -->
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