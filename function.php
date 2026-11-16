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
    

};


function isUser($userId, $userPw){
    Connect();
    try {
        $stmt = $db->prepare("
        ")
    }
    return ;
};

function getUser($userId, $userPw){
    
    return ;
};

function addUser($viewName, $userId, $userPw){
    
    return ;
};

function getQuestion(){
    
    return ;
};

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