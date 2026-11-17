<?php

// 1. データベース接続オブジェクト(PDO)を生成する
// @return PDO

function Connect(){
    $dsn = 'mysql:host=localhost;dbname=ideastock;charset=utf8';
    $user = 'root';
    $password = 'pass';
    try {
        $db = new PDO($dsn, $user ,$password);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);  
        return $db;      
    } catch (PDOException $e) {
        exit('エラー：'. $e->getMessage());
    }
}

// 2. ログインID、パスワードを基にDBに登録されているユーザーか確認する]
// @param string $userId ログインID
// @param string $userPw パスワード
// @return bool true: 登録ユーザー / false: 未登録

function isUser($userId, $userPw){
    $db = Connect();
    $stmt = $db->prepare('SELECT * FROM user WHERE loginId = :userId');
    $stmt->bindValue(':userId',$userId,PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch();

    if($user && password_verify($userPw, $user['password'])) {
        return true;
    }
    return false;
};

// 3. ログインID、パスワードを基にユーザー情報を取得し、連想配列で返却する
 
// @param string $userId ログインID
// @param string $userPw パスワード
// @return array|false array: 利用者情報 / false: DB未登録
function getUser($userId, $userPw){
    $db = Connect();
    $stmt = $db->prepare('SELECT * FROM user WHERE loginId = :userId');
    $stmt->bindValue(':userId',$userId,PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch();

    if($user && password_verify($userPw,$user['password'])){
        return $user;
    }
    return false;
};

// 4. 引数を基に利用者情報をDBに登録する（ログインIDとパスワードはユニーク）
// @param string $viewName 表示名
// @param string $userId ログインID
// @param string $userPw パスワード
// @return bool true: 登録成功 / false: 登録失敗

function addUser($viewName, $userId, $userPw){    
    $result = false; 
    try {
        $db = Connect();
        // パスワードを安全なハッシュ値に変換する
        $hashedPassword = password_hash($userPw, PASSWORD_DEFAULT);
        $stmt = $db->prepare("
        INSERT INTO user(name , loginId , password)
    VALUES(:viewName, :userId, :userPw)"
        );
        $stmt->bindParam(':viewName',$viewName,PDO::PARAM_STR);
        $stmt->bindParam(':userId',$userId,PDO::PARAM_STR);
        $stmt->bindParam(':userPw',$hashedPassword,PDO::PARAM_STR);
        $stmt->execute();
        $result = true;
    } catch (PDOException $e){
        // exit('エラー：' . $e->getMessage());
        $result = false;
    }       
    return $result;
};

// 5. DBから質問表示に必要な情報を2次元連想配列で取得する（新しい順）
// @return array|false

function getQuestion(){    
    $db = Connect();
    $stmt = $db->prepare("SELECT * FROM question WHERE deleteFlg = 0 ORDER BY date DESC ");
    $stmt->execute();
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $questions;
} 

// 6. 登録時点の日時を取得し、質問をDBに登録する

// @param int $userId 質問者のID
// @param string $question 質問
// @return bool true: 登録成功

function addQuestion($userId, $question){
    $db = Connect();
    $stmt = $db->prepare('INSERT INTO question(userId ,question,date)VALUES(:userId,:question,NOW())');
    $stmt->bindValue(':userId',$userId,PDO::PARAM_INT);
    $stmt->bindValue(':question',$question,PDO::PARAM_STR);
    return $stmt->execute();
}

// 7. 質問IDに紐づく質問と回答を論理削除する（排他制御を意識）
 
// @param int $questionId 削除対象の質問ID
// @return bool true: 削除成功
function deleteQuestion($questionId){
    $db = Connect();
    try{
    $db->beginTransaction();
    
    // 質問の論理削除 (deleteFlgを１に)
    $stmt1 = $db->prepare('UPDATE question SET deleteFlg = 1 WHERE id = :questionId');
    $stmt1->bindValue(':questionId',$questionId,PDO::PARAM_INT);
    $stmt1->execute();

    // 質問に紐づく回答の論理削除
    $stmt2 = $db->prepare('UPDATE answer SET deleteFlg = 1 WHERE questionId = :questionId');
    $stmt2->bindValue(':questionId',$questionId,PDO::PARAM_INT);
    $stmt2->execute();
    $db->commit();
    return true;    
} catch (Exception $e){
    $db->rollBack();
    return false;
}
}

// 8. 質問IDに紐つく質問情報を取得する
 
// @param int $questionId 質問ID
// @return array|false

function getQuestionById($questionId){
    $db = Connect();
    $stmt = $db->prepare('SELECT * FROM question WHERE id = questionId AND deleteFlg = 0');
    $stmt->bindValue(':questionId',$questionId,PDO::PARAM_INT);
    $stmt->execute();
    // 1件だけ取得して返す（見つからない場合はfalse）
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// 9. 質問IDに紐つく回答情報を取得する（日付の新しい順、回答番号順） 
// @param int $questionId 質問ID
// @return array|false

function getAnswersByQuestionId($questionId){
    $db = Connect();
    $stmt = $db->prepare('SELECT * FROM answer WHERE questionId = :questionId AND deleteFlg = 0 ORDER BY date DESC, id ASC');
    $stmt->bindValue(':questionId',$questionId,PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 10. 登録時点の日時を取得し、回答をDBに登録する

// @param int $userId 質問者のID（回答者ID）
// @param int $questionId 質問ID
// @param string $answer 回答
// @return bool true: 登録成功

function addAnswer($userId, $questionId, $answer){
    $db = Connect();
    $stmt = $db->prepare('INSERT INTO answer (questionId , userId, answer , date, deleteFlg) VALUES (:questionId, :userId, :answer, NOW(), 0)
    ');
    $stmt->bindValue(':questionId',$questionId,PDO::PARAM_INT);
    $stmt->bindValue(':userId',$userId,PDO::PARAM_INT);
    $stmt->bindValue(':answer',$answer,PDO::PARAM_STR);

    return $stmt->execute();
}

// 11. 質問IDに紐づく質問内容をDBで更新する

// @param int $questionId 更新対象の質問ID
// @param string $question 更新後の質問内容
// @return bool true: 更新成功 / false: 更新失敗

function updateQuestion($questionId, $question){
    $db = Connect();
    $stmt = $db->prepare('UPDATE question SET question = :question WHERE id = :questionId');
    $stmt->bindValue(':question',$question,PDO::PARAM_STR);
    $stmt->bindValue(':questionId',$questionId,PDO::PARAM_INT);
    return $stmt->execute();
}

// 12. 質問IDに紐づく回答一覧をDBから取得する（該当なし・エラー時は空配列）

// @param int $questionId 取得対象の質問ID
// @return array

function getAnswers($questionId){
    $db = Connect();
    $stmt = $db->prepare('SELECT * FROM answer WHERE questionId = :questionId AND deleteFlg = 0 ORDER BY date DESC, id ASC');
    $stmt->bindValue(':questionId',$questionId,PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 13. 回答IDに紐つく回答をDBから論理削除する

// @param int $answerId 削除対象の回答ID
// @return bool true: 削除成功

function deleteAnswer($answerId){
    $db = Connect();
    $stmt = $db->prepare('UPDATE answer SET deleteFlg = 1 WHERE id = :answerId');
    $stmt->bindValue(':answerId',$answerId,PDO::PARAM_INT);
    return $stmt->execute();
}


?>