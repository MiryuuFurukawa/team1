<?php
require_once '../function.php';

echo "<h2>getUser 関数の動作テスト</h2>";

// 1. テストケース：正しいログインIDとパスワード（初期データ: testuser1 / password12）
$result1 = getUser('testuser1', 'password12');
echo "テスト1 (正しいIDとPW): ";
if (is_array($result1) && $result1['name'] === '山田太郎') {
    echo "<span style='color: green;'>【成功】ユーザー情報が連想配列で取得できました！</span><br>";
    echo "<pre>";
    print_r($result1); // 山田の情報が表示されるか確認
    echo "</pre>";
} else {
    echo "<span style='color: red;'>【失敗】正しく配列が返されませんでした</span><br>";
}

// 2. テストケース：間違ったパスワード
$result2 = getUser('testuser1', 'wrong_password');
echo "テスト2 (間違ったPW): ";
if ($result2 === false) {
    echo "<span style='color: green;'>【成功】false が返されました</span><br>";
} else {
    echo "<span style='color: red;'>【失敗】値が返されました（本来はfalseの想定）</span><br>";
}

// 3. テストケース：存在しないログインID
$result3 = getUser('nouser', 'password12');
echo "テスト3 (存在しないID): ";
if ($result3 === false) {
    echo "<span style='color: green;'>【成功】false が返されました</span><br>";
} else {
    echo "<span style='color: red;'>【失敗】値が返されました（本来はfalseの想定）</span><br>";
}
?>