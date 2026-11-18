<?php
require_once '../function.php';

echo "<h2>getQuestion 関数の動作テスト</h2>";

// 1. 質問一覧を取得する
$questions = getQuestion();

if ($questions === false) {
    echo "<span style='color: red;'>【結果】有効な質問はありませんでした (false が返されました)</span><br>";
} elseif (is_array($questions)) {
    echo "<span style='color: green;'>【成功】質問一覧が2次元連想配列で取得できました！</span><br>";
    echo "取得件数: " . count($questions) . "件<br>";

    echo "<h3>取得したデータの中身：</h3>";
    echo "<pre>";
    print_r($questions);
    echo "</pre>";
} else {
    echo "<span style='color: red;'>【失敗】予期しない値が返されました</span><br>";
}
?>