<?php
require_once '../function.php';

echo "<h2>getQuestionById 関数の動作テスト</h2>";

$db = Connect();

// 事前準備：テスト用に有効な質問データを1件登録する
$stmt = $db->prepare('INSERT INTO question (userId, question, date, deleteFlg) VALUES (1, :qText, NOW(), 0)');
$stmt->bindValue(':qText', 'getQuestionByIdテスト用質問: ' . date('Y-m-d H:i:s'), PDO::PARAM_STR);
$stmt->execute();
$validQuestionId = $db->lastInsertId();

// 事前準備2：論理削除された質問データも1件作っておく（deleteFlg = 1）
$stmt2 = $db->prepare('INSERT INTO question (userId, question, date, deleteFlg) VALUES (1, :qText, NOW(), 1)');
$stmt2->bindValue(':qText', '削除済み質問: ' . date('Y-m-d H:i:s'), PDO::PARAM_STR);
$stmt2->execute();
$deletedQuestionId = $db->lastInsertId();

echo "テストデータ準備完了 (有効ID: {$validQuestionId}, 削除済ID: {$deletedQuestionId})<br><br>";

// 1. テストケース：存在する有効な質問IDを指定
echo "テスト1 (存在する有効なID): ";
$result1 = getQuestionById($validQuestionId);
if (is_array($result1) && $result1['id'] == $validQuestionId) {
    echo "<span style='color: green;'>【成功】質問情報が連想配列で取得できました！</span><br>";
    echo "<pre>";
    print_r($result1);
    echo "</pre>";
} else {
    echo "<span style='color: red;'>【失敗】正しくデータを取得できませんでした</span><br>";
}

// 2. テストケース：存在しないIDを指定
echo "テスト2 (存在しないID: 999999): ";
$result2 = getQuestionById(999999);
if ($result2 === false) {
    echo "<span style='color: green;'>【成功】false が返されました</span><br>";
} else {
    echo "<span style='color: red;'>【失敗】値が返されてしまいました（本来はfalseの想定）</span><br>";
}

// 3. テストケース：論理削除されたIDを指定（deleteFlg = 1 のため取得できないはず）
echo "テスト3 (論理削除されたID): ";
$result3 = getQuestionById($deletedQuestionId);
if ($result3 === false) {
    echo "<span style='color: green;'>【成功】削除済みのため false が返されました</span><br>";
} else {
    echo "<span style='color: red;'>【失敗】削除済みなのにデータが取得されてしまいました</span><br>";
}
?>