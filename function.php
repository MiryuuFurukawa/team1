<?php

function Connect(){
    $dsn = 'mysql:host=localhost;dbname=ideastock;charset=utf8';
    $user = 'user';
    $password = 'password';
    return $db = new PDO($dsn, $user ,$password);
};


function isUser($userId, $userPw){
    
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