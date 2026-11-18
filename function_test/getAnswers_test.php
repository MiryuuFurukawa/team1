<?php
require_once '../function.php';

echo "<h2>getAnswers 関数の動作テスト</h2>";

$db = Connect();

// 1. 事前準備：テスト用の質問を1件登録する
$stmtQ = $db->prepare('INSERT INTO question (userId, question, date, deleteFlg) VALUES (1, :qText, NOW(), 0)');
$stmtQ->bindValue(':qText', 'getAnswersテスト用質問: ' . date('Y-m-d H:i:s'), PDO::PARAM_STR);
$stmtQ->execute();
$testQuestionId = $db->lastInsertId();

// 2. その質問に対してテスト用の回答を複数件登録する
// 回答1（少し古い日時）
$stmtA1 = $db->prepare('INSERT INTO answer (questionId, userId, answer, date, deleteFlg) VALUES (:qId, 1, :aText, :aDate, 0)');
$stmtA1->bindValue(':qId', $testQuestionId, PDO::PARAM_INT);
$stmtA1->bindValue(':aText', '1番古い回答（一番下に表示されるはず）', PDO::PARAM_STR);
$stmtA1->bindValue(':aDate', date('Y-m-d H:i:s', strtotime('-30 minutes')), PDO::PARAM_STR);
$stmtA1->execute();

// 回答2（新しい日時）
$stmtA2 = $db->prepare('INSERT INTO answer (questionId, userId, answer, date, deleteFlg) VALUES (:qId, 1, :aText, :aDate, 0)');
$stmtA2->bindValue(':qId', $testQuestionId, PDO::PARAM_INT);
$stmtA2->bindValue(':aText', '新しい回答（上に表示されるはず）', PDO::PARAM_STR);
$stmtA2->bindValue(':aDate', date('Y-m-d H:i:s'), PDO::PARAM_STR);
$stmtA2->execute();

// 論理削除された回答（取得されないかの確認用）
$stmtDel = $db->prepare('INSERT INTO answer (questionId, userId, answer, date, deleteFlg) VALUES (:qId, 1, :aText, NOW(), 1)');
$stmtDel->bindValue(':qId', $testQuestionId, PDO::PARAM_INT);
$stmtDel->bindValue(':aText', '削除された回答（除外されるはず）', PDO::PARAM_STR);
$stmtDel->execute();

echo "テストデータ準備完了 (質問ID: {$testQuestionId})<br><br>";

// 3. テストケース：存在する質問IDで回答一覧を取得
echo "テスト1 (有効な質問IDでの回答一覧取得): ";
$answers = getAnswers($testQuestionId);

if (is_array($answers)) {
    echo "<span style='color: green;'>【成功】配列として取得できました（取得件数: " . count($answers) . "件）</span><br>";
    echo "<pre>";
    print_r($answers);
    echo "</pre>";
} else {
    echo "<span style='color: red;'>【失敗】配列以外の値が返されました</span><br>";
}

// 4. テストケース：回答が1件もない（または存在しない）質問IDを指定 -> 空配列が返るか
echo "テスト2 (存在しない質問ID: 999999): ";
$noAnswers = getAnswers(999999);

if (is_array($noAnswers) && empty($noAnswers)) {
    echo "<span style='color: green;'>【成功】該当なしのため空配列（[]）が返されました！</span><br>";
} else {
    echo "<span style='color: red;'>【失敗】空配列が返されませんでした</span><br>";
}
?>