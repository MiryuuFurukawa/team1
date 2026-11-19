<?php
require_once '../function.php';

echo "<h2>getAnswersByQuestionId 関数の動作テスト</h2>";

$db = Connect();

// 事前準備1：テーブル制約（loginIdは8〜10文字、passwordは6〜10文字）を満たすテスト用ユーザーを登録
$testLoginId = 'test' . mt_rand(1000, 9999);
$testPass = 'password';

$stmtU = $db->prepare('INSERT INTO user (name, loginId, password) VALUES (:name, :loginId, :password)');
$stmtU->bindValue(':name', '回答テスト投稿者', PDO::PARAM_STR);
$stmtU->bindValue(':loginId', $testLoginId, PDO::PARAM_STR);
$stmtU->bindValue(':password', $testPass, PDO::PARAM_STR);
$stmtU->execute();
$testUserId = $db->lastInsertId();

// 事前準備2：テスト用の質問を1件登録する
$stmtQ = $db->prepare('INSERT INTO question (userId, question, date, deleteFlg) VALUES (:userId, :qText, NOW(), 0)');
$stmtQ->bindValue(':userId', $testUserId, PDO::PARAM_INT);
$stmtQ->bindValue(':qText', '回答取得テスト用質問: ' . date('Y-m-d H:i:s'), PDO::PARAM_STR);
$stmtQ->execute();
$testQuestionId = $db->lastInsertId();

// 事前準備3：その質問に対して複数のテスト用回答を登録する
// 回答1（1時間前の古い日時）
$stmtA1 = $db->prepare('INSERT INTO answer (questionId, userId, answer, date, deleteFlg) VALUES (:qId, :uId, :aText, :aDate, 0)');
$stmtA1->bindValue(':qId', $testQuestionId, PDO::PARAM_INT);
$stmtA1->bindValue(':uId', $testUserId, PDO::PARAM_INT);
$stmtA1->bindValue(':aText', '1番古い回答（表示は最後になるはず）', PDO::PARAM_STR);
$stmtA1->bindValue(':aDate', date('Y-m-d H:i:s', strtotime('-1 hour')), PDO::PARAM_STR);
$stmtA1->execute();

// 回答2（現在の日時・A）
$stmtA2 = $db->prepare('INSERT INTO answer (questionId, userId, answer, date, deleteFlg) VALUES (:qId, :uId, :aText, :aDate, 0)');
$stmtA2->bindValue(':qId', $testQuestionId, PDO::PARAM_INT);
$stmtA2->bindValue(':uId', $testUserId, PDO::PARAM_INT);
$stmtA2->bindValue(':aText', '新しい回答・その1', PDO::PARAM_STR);
$stmtA2->bindValue(':aDate', date('Y-m-d H:i:s'), PDO::PARAM_STR);
$stmtA2->execute();

// 回答3（回答2と同じ日時で登録し、idの昇順ソートを確認する）
$stmtA3 = $db->prepare('INSERT INTO answer (questionId, userId, answer, date, deleteFlg) VALUES (:qId, :uId, :aText, :aDate, 0)');
$stmtA3->bindValue(':qId', $testQuestionId, PDO::PARAM_INT);
$stmtA3->bindValue(':uId', $testUserId, PDO::PARAM_INT);
$stmtA3->bindValue(':aText', '新しい回答・その2（同日時でIDが後ろ）', PDO::PARAM_STR);
$stmtA3->bindValue(':aDate', date('Y-m-d H:i:s'), PDO::PARAM_STR);
$stmtA3->execute();

// 事前準備4：論理削除された回答も1件作っておく（deleteFlg = 1）
$stmtDel = $db->prepare('INSERT INTO answer (questionId, userId, answer, date, deleteFlg) VALUES (:qId, :uId, :aText, NOW(), 1)');
$stmtDel->bindValue(':qId', $testQuestionId, PDO::PARAM_INT);
$stmtDel->bindValue(':uId', $testUserId, PDO::PARAM_INT);
$stmtDel->bindValue(':aText', '削除された回答（取得されないはず）', PDO::PARAM_STR);
$stmtDel->execute();

echo "テストデータ準備完了 (質問ID: {$testQuestionId})<br><br>";

// 1. テストケース：存在する質問IDで回答を取得
echo "テスト1 (有効な質問IDでの回答取得): ";
$answers = getAnswersByQuestionId($testQuestionId);

if (is_array($answers)) {
    echo "<span style='color: green;'>【成功】回答情報が2次元連想配列で取得できました！（取得件数: " . count($answers) . "件）</span><br>";
    echo "<pre>";
    print_r($answers);
    echo "</pre>";

    // 結合された answererName が正しく含まれているかチェック
    if (isset($answers[0]['answererName']) && $answers[0]['answererName'] === '回答テスト投稿者') {
        echo "<span style='color: green;'>-> 結合された 'answererName' も正しく含まれています！</span><br><br>";
    } else {
        echo "<span style='color: red;'>-> 【警告】'answererName' が正しく取得できていません。</span><br><br>";
    }
} else {
    echo "<span style='color: red;'>【失敗】配列ではなく false が返されました</span><br><br>";
}

// 2. テストケース：回答が1件もない存在しない質問IDを指定 -> false が返るか
echo "テスト2 (存在しない質問ID: 999999): ";
$noAnswers = getAnswersByQuestionId(999999);
if ($noAnswers === false) {
    echo "<span style='color: green;'>【成功】データが空のため正しく false が返されました！</span><br>";
} else {
    echo "<span style='color: red;'>【失敗】false 以外の値が返されました</span><br>";
}
?>