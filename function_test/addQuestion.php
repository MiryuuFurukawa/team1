<?php
require_once '../function.php';

echo "<h2>addQuestion 関数の動作テスト</h2>";

// 1. テストデータの準備（初期データで存在する user id = 1 を使用）
$testUserId = 1;
$testQuestionText = 'テスト質問：皆さんは普段どのエディタを使っていますか？ (' . date('Y-m-d H:i:s') . ')';

echo "質問登録テスト中...<br>";

// 2. addQuestion 関数の実行
$result = addQuestion($testUserId, $testQuestionText);

if ($result === true) {
    echo "<span style='color: green;'>【成功】addQuestion が true を返しました（登録成功）</span><br>";

    // 3. 実際に getQuestion() で登録された質問が新着（先頭）として取得できるか確認
    $questions = getQuestion();
    if ($questions && $questions[0]['question'] === $testQuestionText) {
        echo "<span style='color: green;'>【確認OK】登録した質問が getQuestion() の先頭から正常に取得できました！</span><br>";
        echo "<h3>登録された最新の質問データ：</h3>";
        echo "<pre>";
        print_r($questions[0]);
        echo "</pre>";
    } else {
        echo "<span style='color: red;'>【確認NG】登録は成功しましたが、getQuestion で正しく取得できませんでした</span><br>";
    }
} else {
    echo "<span style='color: red;'>【失敗】addQuestion が false を返しました</span><br>";
}
?>