<?php
require_once 'function.php';
startSessionIfNeeded();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['logout'])) {
        logoutProcess();
    }

    $userId = $_POST['userId'] ?? '';
    $userPw = $_POST['userPw'] ?? '';
    $page = $_POST['page'] ?? '';
    $questionId = $_POST['questionId'] ?? '';

    // ログイン処理を実行（成功時は各画面へ、失敗時はそのままログイン画面へリダイレクト）
    loginProcess($userId, $userPw, $page, $questionId);
} else {
    header('Location: index.php');
    exit;
}