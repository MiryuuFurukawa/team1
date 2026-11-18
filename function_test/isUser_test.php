<?php
// function.php のファイル名やパスに合わせて調整してください
require_once '../function.php';

echo "<h2>isUser 関数の動作テスト</h2>";

// 1. テストケース：正しいログインIDとパスワード（初期データ: testuser1 / password12）
$result1 = isUser('testuser1', 'password12');
echo "テスト1 (正しいIDとPW): ";
if ($result1 === true) {
    echo "<span style='color: green;'>【成功】true が返されました</span><br>";
} else {
    echo "<span style='color: red;'>【失敗】false が返されました（本来はtrueの想定）</span><br>";
}

// 2. テストケース：間違ったパスワード
$result2 = isUser('testuser1', 'wrong_password');
echo "テスト2 (間違ったPW): ";
if ($result2 === false) {
    echo "<span style='color: green;'>【成功】false が返されました</span><br>";
} else {
    echo "<span style='color: red;'>【失敗】true が返されました（本来はfalseの想定）</span><br>";
}

// 3. テストケース：存在しないログインID
$result3 = isUser('nouser', 'password12');
echo "テスト3 (存在しないID): ";
if ($result3 === false) {
    echo "<span style='color: green;'>【成功】false が返されました</span><br>";
} else {
    echo "<span style='color: red;'>【失敗】true が返されました（本来はfalseの想定）</span><br>";
}
?>