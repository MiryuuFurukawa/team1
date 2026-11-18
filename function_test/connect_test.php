<?php
// ※元のファイル名に合わせて変更してください
require_once '../function.php';

try {
    // Connect関数を呼び出す
    $db = Connect();

    if ($db instanceof PDO) {
        echo "<h2>接続成功！</h2>";
        echo "データベース 'ideastock' に正常に接続できました。";

        $stmt = $db->query("SELECT * FROM user");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<h3>登録されているユーザー一覧：</h3>";
        echo "<pre>";
        print_r($users);
        echo "</pre>";
    }
} catch (Exception $e) {
    echo "<h2>接続失敗…</h2>";
    echo "エラーメッセージ: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}
?>