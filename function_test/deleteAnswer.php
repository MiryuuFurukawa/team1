<?php
require_once '../function.php';

echo "<h2>deleteAnswer 関数の動作テスト</h2>";

$db = Connect();

// 1. 事前準備：テスト用の質問を1件登録する
$stmtQ = $db->prepare('INSERT INTO question (userId, question, date, deleteFlg) VALUES (1, :qText, NOW(), 0)');
$stmtQ->bindValue(':qText', 'deleteAnswerテスト用質問: ' . date('Y-m-d H:i:s'), PDO::PARAM_STR);
$stmtQ->execute();
$testQuestionId = $db->lastInsertId();

// 2. 事前準備：その質問に対してテスト用の回答を1件登録する
$stmtA = $db->prepare('INSERT INTO answer (questionId, userId, answer, date, deleteFlg) VALUES (:qId, 1, :aText, NOW(), 0)');
$stmtA->bindValue(':qId', $testQuestionId, PDO::PARAM_INT);
$stmtA->bindValue(':aText', '論理削除されるテスト回答です');
$stmtA->execute();
$testAnswerId = $db->lastInsertId();

echo "テストデータ準備完了 (質問ID: {$testQuestionId}, 回答ID: {$testAnswerId})<br><br>";

// 3. 削除前の状態確認（取得できるはず）
$beforeAnswers = getAnswers($testQuestionId);
echo "削除前の回答数: " . count($beforeAnswers) . "件<br>";

// 4. deleteAnswer 関数の実行
echo "deleteAnswer({$testAnswerId}) を実行中...<br>";
$result = deleteAnswer($testAnswerId);

if ($result === true) {
    echo "<span style='color: green;'>【成功】deleteAnswer が true を返しました（SQL実行成功）</span><br>";

    // 5. データベース上で deleteFlg が 1 になっているか直接確認
    $stmtCheck = $db->prepare('SELECT id, answer, deleteFlg FROM answer WHERE id = :id');
    $stmtCheck->bindValue(':id', $testAnswerId, PDO::PARAM_INT);
    $stmtCheck->execute();
    $answerData = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($answerData && $answerData['deleteFlg'] == 1) {
        echo "<span style='color: green;'>【確認OK】データベース上の deleteFlg が確実に `1` になっています！</span><br>";
    } else {
        echo "<span style='color: red;'>【確認NG】deleteFlg が 1 に更新されていません</span><br>";
    }

    // 6. getAnswers() で取得した際に一覧から除外されているか確認
    $afterAnswers = getAnswers($testQuestionId);
    if (empty($afterAnswers)) {
        echo "<span style='color: green;'>【確認OK】getAnswers() の結果からも正常に除外されています（空配列）！</span><br>";
    } else {
        echo "<span style='color: red;'>【確認NG】削除済みなのに getAnswers() で取得されてしまいました</span><br>";
    }

} else {
    echo "<span style='color: red;'>【失敗】deleteAnswer が false を返しました</span><br>";
}
?>