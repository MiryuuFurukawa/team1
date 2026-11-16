create database if not exists ideastock;

use ideastock;

create table if not exists user(
    id int auto_increment,
    loginId varchar(10) not null,
    password varchar(10) not null,
    name varchar(10) not null,
    primary key (id),
    constraint unique_id_pass unique(loginId,password),
    constraint check_login_id_length check (char_length(loginId) >= 8),
    constraint check_password_length check (char_length(password) >= 6)
);

create table if not exists question(
    id int auto_increment,
    userId int not null,
    question varchar(256) not null,
    date datetime not null,
    deleteFlg tinyint(1) not null default 0,
    primary key (id),
    constraint foreign_key_question_user foreign key(userId) references user(id)
);

create table if not exists answer(
    id bigint auto_increment,
    questionId int not null,
    userId int not null,
    answer varchar(256) not null,
    date datetime not null,
    deleteFlg tinyint(1) not null default 0,
    primary key (id),
    constraint foreign_key_answer_question foreign key(questionId) references question(id),
    constraint foreign_key_answer_user foreign key(userId) references user(id)
);

desc user;
desc question;
desc answer;

INSERT INTO `user` (loginId, password, name) 
VALUES ('testuser1', 'password12', '山田太郎');

INSERT INTO `question` (userId, question, date, deleteFlg) 
VALUES (1, '皆さんはいつから、PHPを書いていますか？', '2026-06-07 10:00:00', 0);

INSERT INTO `answer` (questionId, userId, answer, date, deleteFlg) 
VALUES (1, 1, '私は１０年以上前から書いています', '2026-06-07 11:30:00', 0);

select * from user;
select * from question;
select * from answer;