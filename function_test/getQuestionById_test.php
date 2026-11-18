<?php
require_once '../function.php';

echo "<h2>getQuestionById 関数の動作テスト</h2>";

$db = Connect();

// テーブル定義の制約（loginIdは8〜10文字、passwordは6〜10文字）を満たすテスト用ユーザーを生成
// 例: "test" (4文字) + 4桁のランダム数字 = 8文字
$testLoginId = 'test' . mt_rand(1000, 9999);
$testPass = 'password'; // 8文字（6文字以上の制約クリア）

$stmtU = $db->prepare('INSERT INTO user (name, loginId, password) VALUES (:name, :loginId, :password)');
$stmtU->bindValue(':name', 'テスト投稿者', PDO::PARAM_STR);
$stmtU->bindValue(':loginId', $testLoginId, PDO::PARAM_STR);
$stmtU->bindValue(':password', $testPass, PDO::PARAM_STR);
$stmtU->execute();
$testUserId = $db->lastInsertId();

// 事前準備2：テスト用の質問を1件登録する
$testQuestionText = '詳細取得テスト用質問: ' . date('Y-m-d H:i:s');
$stmtQ = $db->prepare('INSERT INTO question (userId, question, date, deleteFlg) VALUES (:userId, :qText, NOW(), 0)');
$stmtQ->bindValue(':userId', $testUserId, PDO::PARAM_INT);
$stmtQ->bindValue(':qText', $testQuestionText, PDO::PARAM_STR);
$stmtQ->execute();
$testQuestionId = $db->lastInsertId();

// 事前準備3：比較用に「論理削除された質問」も1件作っておく（deleteFlg = 1）
$stmtDelQ = $db->prepare('INSERT INTO question (userId, question, date, deleteFlg) VALUES (:userId, :qText, NOW(), 1)');
$stmtDelQ->bindValue(':userId', $testUserId, PDO::PARAM_INT);
$stmtDelQ->bindValue(':qText', '削除された質問（取得されないはず）', PDO::PARAM_STR);
$stmtDelQ->execute();
$deletedQuestionId = $db->lastInsertId();

echo "テストデータ準備完了 (質問ID: {$testQuestionId})<br><br>";

// 1. テストケース：存在する有効な質問IDで取得
echo "テスト1 (有効な質問IDでの取得): ";
$question = getQuestionById($testQuestionId);

if (is_array($question)) {
    echo "<span style='color: green;'>【成功】質問情報が連想配列で取得できました！</span><br>";
    echo "<pre>";
    print_r($question);
    echo "</pre>";

    if (isset($question['userName']) && $question['userName'] === 'テスト投稿者' && $question['question'] === $testQuestionText) {
        echo "<span style='color: green;'>-> 結合された 'userName' および 'question' の内容も正確です！</span><br><br>";
    } else {
        echo "<span style='color: red;'>-> 【警告】取得したデータの内容が期待値と一致しません。</span><br><br>";
    }
} else {
    echo "<span style='color: red;'>【失敗】配列ではなく false が返されました</span><br><br>";
}

// 2. テストケース：論理削除された質問IDを指定 -> false が返るか
echo "テスト2 (論理削除された質問ID: {$deletedQuestionId}): ";
$resultDeleted = getQuestionById($deletedQuestionId);
if ($resultDeleted === false) {
    echo "<span style='color: green;'>【成功】論理削除されているため正しく false が返されました！</span><br><br>";
} else {
    echo "<span style='color: red;'>【失敗】削除されているのにデータが取得されてしまいました</span><br><br>";
}

// 3. テストケース：存在しない質問IDを指定 -> false が返るか
echo "テスト3 (存在しない質問ID: 999999): ";
$resultNotExist = getQuestionById(999999);
if ($resultNotExist === false) {
    echo "<span style='color: green;'>【成功】存在しないIDのため正しく false が返されました！</span><br>";
} else {
    echo "<span style='color: red;'>【失敗】存在しないIDなのにデータが返されてしまいました</span><br>";
}
?>