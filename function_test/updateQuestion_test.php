<?php
require_once '../function.php';

echo "<h2>updateQuestion 関数の動作テスト</h2>";

$db = Connect();

// 1. 事前準備：テスト用の質問を1件登録する（userId = 1 の testuser1 を想定）
$testUserId = 1;
$originalText = '更新前テスト質問：元の質問文 (' . date('Y-m-d H:i:s') . ')';

$stmtQ = $db->prepare('INSERT INTO question (userId, question, date, deleteFlg) VALUES (:userId, :qText, NOW(), 0)');
$stmtQ->bindValue(':userId', $testUserId, PDO::PARAM_INT);
$stmtQ->bindValue(':qText', $originalText, PDO::PARAM_STR);
$stmtQ->execute();
$testQuestionId = $db->lastInsertId();

$updatedText = '更新後テスト質問：書き換えられた新しい質問文です！';

echo "対象の質問ID: {$testQuestionId}<br>";
echo "元の質問文: 「{$originalText}」<br>";
echo "更新後の質問文に変更中...<br><br>";

// 2. updateQuestion 関数の実行
$result = updateQuestion($testQuestionId, $updatedText);

if ($result === true) {
    echo "<span style='color: green;'>【成功】updateQuestion が true を返しました（SQL実行成功）</span><br>";

    // 3. 実際に getQuestionById() で更新された内容が取得できるか確認
    $questionData = getQuestionById($testQuestionId);

    if (is_array($questionData) && $questionData['question'] === $updatedText) {
        echo "<span style='color: green;'>【確認OK】データベース上の質問内容が正しく新しい文章に更新されています！</span><br>";
        echo "<h3>取得された最新の質問データ：</h3>";
        echo "<pre>";
        print_r($questionData);
        echo "</pre>";
    } else {
        echo "<span style='color: red;'>【確認NG】SQLは成功しましたが、取得した内容が更新後の文章と一致しません</span><br>";
    }
} else {
    echo "<span style='color: red;'>【失敗】updateQuestion が false を返しました</span><br>";
}
?>