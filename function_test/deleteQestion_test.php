<?php
require_once '../function.php';

// 消したい質問のIDを指定してください
$targetQuestionId = 2; // ← 削除したい実際のquestionのidに変更してください

echo "<h2>質問の削除処理</h2>";

$result = deleteQuestion($targetQuestionId);

if ($result === true) {
    echo "<span style='color: green;'>【成功】質問ID: {$targetQuestionId} を論理削除しました！</span><br>";

    // --- ここから追加：DBから deleteFlg の値を確認する処理 ---
    $db = Connect();
    $stmt = $db->prepare('SELECT id, question, deleteFlg FROM question WHERE id = :id');
    $stmt->bindValue(':id', $targetQuestionId, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        echo "データベース上の確認: 質問ID {$row['id']} の deleteFlg = <b>{$row['deleteFlg']}</b> ";
        if ($row['deleteFlg'] == 1) {
            echo "<span style='color: green;'>(正常に 1 に更新されています！)</span><br>";
        } else {
            echo "<span style='color: red;'>(⚠️ まだ 0 のままです)</span><br>";
        }
    } else {
        echo "<span style='color: red;'>指定したIDの質問がデータベースに見つかりませんでした。</span><br>";
    }
    // ------------------------------------------------------

} else {
    echo "<span style='color: red;'>【失敗】削除に失敗しました（IDが存在しない可能性があります）</span><br>";
}
?>