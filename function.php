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
    $stmt = $db->prepare('SELECT * FROM user WHERE userId = :userId');
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
    $stmt = $db->prepare('SELECT * FROM user WHERE userId = :userId');
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
        INSERT INTO user(name , userid , password)
    VALUES(:viewName, :userId, :userPw)"
        );
        $stmt->bindParam(':viewName',$viewName,PDO::PARAM_STR);
        $stmt->bindParam(':userId',$userId,PDO::PARAM_STR);
        $stmt->bindParam(':userPw',$userPw,PDO::PARAM_STR);
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
    $stmt = $db->prepare('INSERT INTO question(userId ,question,created_at)VALUES(:userId,:question,NOW())');
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


function getQuestionById($questionId){
    
    return ;
};

function getAnswersByQuestionId($questionId){
    
    return ;
};

function addAnswer($userId, $questionid, $answer){
    
    return ;
};

function updateQuestion($questionId, $question ){
    
    return ;
};

function getAnswers($questionId){
    
    return ;
};

function deleteAnswer($answerId){
    
    return ;
};


?>