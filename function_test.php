<?php
// ※元のファイル名に合わせて変更してください
require_once 'functions.php';

// ブラウザで見やすくするためのスタイル（CLI実行時は無視されます）
if (php_sapi_name() !== 'cli') {
    echo '<pre style="font-family: monospace; line-height: 1.5; background: #f4f4f4; padding: 15px;">';
}

// テスト結果を判定して出力するヘルパー関数
function assertTest($testName, $condition, $detail = '')
{
    $status = $condition ? "[ OK ]" : "[ NG ]";
    echo "{$status} {$testName}";
    if (!$condition && $detail !== '') {
        echo " -> 詳細: {$detail}";
    }
    echo "\n";
}

echo "========================================\n";
echo " データベース関数 動作確認テスト開始\n";
echo "========================================\n\n";

// テスト用のユニークなデータ（毎回違うIDになるようにタイムスタンプ付与）
$suffix = time();
$testViewName = "テスト太郎_{$suffix}";
$testLoginId  = "test_user_{$suffix}";
$testPassword = "Password123!";

// --------------------------------------------------
// 1. Connect() のテスト
// --------------------------------------------------
$db = Connect();
assertTest("1. Connect(): DB接続オブジェクトの取得", $db instanceof PDO);

// --------------------------------------------------
// 4. addUser() のテスト（後続テストのため先に実行）
// --------------------------------------------------
$resAddUser = addUser($testViewName, $testLoginId, $testPassword);
assertTest("4. addUser(): 新規ユーザー登録（正常）", $resAddUser === true);

$resAddDupUser = addUser($testViewName, $testLoginId, $testPassword);
assertTest("4. addUser(): 重複ログインIDの登録拒否（異常系）", $resAddDupUser === false);

// --------------------------------------------------
// 2. isUser() のテスト
// --------------------------------------------------
assertTest("2. isUser(): 正しいID・PWでtrue", isUser($testLoginId, $testPassword) === true);
assertTest("2. isUser(): 誤ったPWでfalse", isUser($testLoginId, "WrongPass") === false);
assertTest("2. isUser(): 存在しないIDでfalse", isUser("no_such_user", $testPassword) === false);

// --------------------------------------------------
// 3. getUser() のテスト
// --------------------------------------------------
$user = getUser($testLoginId, $testPassword);
assertTest("3. getUser(): ユーザー情報の配列取得", is_array($user) && $user['name'] === $testViewName);
assertTest("3. getUser(): 誤ったPWでfalse", getUser($testLoginId, "WrongPass") === false);

// 以降のテスト用に登録したユーザーのIDを取得
$userId = $user['id'] ?? 1;

// --------------------------------------------------
// 6. addQuestion() のテスト
// --------------------------------------------------
$questionText = "テスト質問内容_{$suffix}";
$resAddQ = addQuestion($userId, $questionText);
assertTest("6. addQuestion(): 新規質問の登録", $resAddQ === true);

// --------------------------------------------------
// 5. getQuestion() のテスト
// --------------------------------------------------
$questions = getQuestion();
$latestQuestion = $questions[0] ?? null; // 新しい順なので先頭を取得
$questionId = $latestQuestion['id'] ?? null;

assertTest(
    "5. getQuestion(): 質問一覧取得＆最新が先頭にあるか",
    is_array($questions) && ($latestQuestion['question'] ?? '') === $questionText
);

// --------------------------------------------------
// 8. getQuestionById() のテスト
// --------------------------------------------------
// ※元のコードの `:questionId` のコロン抜けを修正してから実行してください
$fetchedQ = getQuestionById($questionId);
assertTest("8. getQuestionById(): ID指定で質問1件取得", is_array($fetchedQ) && $fetchedQ['id'] == $questionId);
assertTest("8. getQuestionById(): 存在しないIDでfalse", getQuestionById(999999999) === false);

// --------------------------------------------------
// 11. updateQuestion() のテスト
// --------------------------------------------------
$updatedText = "更新後の質問内容_{$suffix}";
$resUpdateQ = updateQuestion($questionId, $updatedText);
$afterUpdateQ = getQuestionById($questionId);
assertTest(
    "11. updateQuestion(): 質問内容の更新確認",
    $resUpdateQ === true && ($afterUpdateQ['question'] ?? '') === $updatedText
);

// --------------------------------------------------
// 10. addAnswer() のテスト
// --------------------------------------------------
$answerText1 = "テスト回答1_{$suffix}";
$answerText2 = "テスト回答2_{$suffix}";
$resAddA1 = addAnswer($userId, $questionId, $answerText1);
$resAddA2 = addAnswer($userId, $questionId, $answerText2);
assertTest("10. addAnswer(): 回答の登録（2件）", $resAddA1 === true && $resAddA2 === true);

// --------------------------------------------------
// 9. getAnswersByQuestionId() & 12. getAnswers() のテスト
// --------------------------------------------------
$answersById = getAnswersByQuestionId($questionId);
assertTest("9. getAnswersByQuestionId(): 回答一覧取得（2件）", is_array($answersById) && count($answersById) === 2);

$answers = getAnswers($questionId);
assertTest("12. getAnswers(): 回答一覧取得（2件）", is_array($answers) && count($answers) === 2);
assertTest("12. getAnswers(): 存在しない質問IDで空配列", getAnswers(999999999) === []);

// --------------------------------------------------
// 13. deleteAnswer() のテスト
// --------------------------------------------------
$targetAnswerId = $answers[0]['id'] ?? null;
$resDelA = deleteAnswer($targetAnswerId);
$afterDelAnswers = getAnswers($questionId);
assertTest(
    "13. deleteAnswer(): 回答1件の論理削除（残り1件になるか）",
    $resDelA === true && count($afterDelAnswers) === 1
);

// --------------------------------------------------
// 7. deleteQuestion() のテスト（質問と紐づく回答の論理削除）
// --------------------------------------------------
$resDelQ = deleteQuestion($questionId);
$afterDelQ = getQuestionById($questionId);
$afterDelQAnswers = getAnswers($questionId);

assertTest("7. deleteQuestion(): 質問の論理削除成功", $resDelQ === true && $afterDelQ === false);
assertTest("7. deleteQuestion(): 紐づく残りの回答も論理削除されているか", count($afterDelQAnswers) === 0);

echo "\n========================================\n";
echo " テスト終了\n";
echo "========================================\n";

if (php_sapi_name() !== 'cli') {
    echo '</pre>';
}