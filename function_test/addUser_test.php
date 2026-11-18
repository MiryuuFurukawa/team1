<?php
require_once '../function.php';

echo "<h2>addUser 関数・ログイン確認テスト (testuser2)</h2>";

$testViewName = 'テスト二郎';
$testUserId = 'testuser2';
$testUserPw = 'password12';

// 既に登録されている場合に備えて一度確認するか、そのまま追加テストを実行
echo "ユーザー登録テスト中... (ログインID: {$testUserId})<br>";

$result = addUser($testViewName, $testUserId, $testUserPw);

if ($result === true) {
    echo "<span style='color: green;'>【成功】testuser2 の登録に成功しました！</span><br>";
} else {
    echo "<span style='color: orange;'>【注意】登録に失敗しました（すでに testuser2 が登録されている可能性があります）</span><br>";
}

// 登録した（または既存の）testuser2 で isUser / getUser が正しく動くか確認
$loginCheck = isUser($testUserId, $testUserPw);
if ($loginCheck === true) {
    echo "<span style='color: green;'>【確認OK】testuser2 での isUser 認証が成功しました！</span><br>";
} else {
    echo "<span style='color: red;'>【確認NG】testuser2 での認証に失敗しました</span><br>";
}

$userinfo = getUser($testUserId, $testUserPw);
if (is_array($userinfo)) {
    echo "<span style='color: green;'>【確認OK】getUser で testuser2 の情報が取得できました：</span>";
    echo "<pre>";
    print_r($userinfo);
    echo "</pre>";
}
?>