<?php

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


function isUser($userId, $userPw){
    $db = Connect();
    $stmt = $db->prepare('SELECT * FROM user WHERE userId = :userId');
    $stmt->execute();
    // ここからがんばれ！

    return ;
};

function getUser($userId, $userPw){
    $db = Connect();
    $sql = 'SELECT * FROM user WHERE userId = :userId';
// ここからもがんばれ！
    return ;
};

function addUser($viewName, $userId, $userPw){    
    $result = false; 
    try {
        $db = Connect();
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

function getQuestion(){    
    $db = Connect();
    $stmt = $db->prepare("SELECT * FROM question WHERE deleteFlg = 0 ORDER BY date DESC ");
    $stmt->execute();
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $questions;

} 

function addQuestion($userId, $question){
    
    return ;
};

function deleteQuestion($questionId){
    
    return ;
};

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