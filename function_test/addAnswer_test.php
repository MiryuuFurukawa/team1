<?php
require_once '../function.php';

echo "<h2>addAnswer 関数の動作テスト</h2>";

$db = Connect();

// 1. 事前準備：テスト用の質問を1件登録する（userId = 1 の testuser1 を想定）
$testUserId = 1;
$stmtQ = $db->prepare('INSERT INTO question (userId, question, date, deleteFlg) VALUES (:userId, :qText, NOW(), 0)');
$stmtQ->bindValue(':userId', $testUserId, PDO::PARAM_INT);
$stmtQ->bindValue(':qText', '回答追加テスト用質問: ' . date('Y-m-d H:i:s'), PDO::PARAM_STR);
$stmtQ->execute();
$testQuestionId = $db->lastInsertId();

$testAnswerText = 'テスト回答です。PHPのPDOとプリペアードステートメントは便利ですね！ (' . date('H:i:s') . ')';

echo "回答登録テスト中 (質問ID: {$testQuestionId})...<br>";

// 2. addAnswer 関数の実行
$result = addAnswer($testUserId, $testQuestionId, $testAnswerText);

if ($result === true) {
    echo "<span style='color: green;'>【成功】addAnswer が true を返しました（登録成功）</span><br>";

    // 3. 実際に getAnswersByQuestionId() で登録された回答が取得できるか確認
    $answers = getAnswersByQuestionId($testQuestionId);
    
    if (is_array($answers) && count($answers) > 0 && $answers[0]['answer'] === $testAnswerText) {
        echo "<span style='color: green;'>【確認OK】登録した回答が getAnswersByQuestionId() で正常に取得できました！</span><br>";
        echo "<h3>取得された回答データ：</h3>";
        echo "<pre>";
        print_r($answers[0]);
        echo "</pre>";
    } else {
        echo "<span style='color: red;'>【確認NG】登録は成功しましたが、正しく取得できませんでした</span><br>";
    }
} else {
    echo "<span style='color: red;'>【失敗】addAnswer が false を返しました</span><br>";
}
?>