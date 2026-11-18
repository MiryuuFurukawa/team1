<?php
date_default_timezone_set('Asia/Tokyo');
// 1. データベース接続オブジェクト(PDO)を生成する
// @return PDO

function Connect()
{
    $dsn = 'mysql:host=localhost;dbname=ideastock;charset=utf8';
    $user = 'root';
    $password = 'pass';
    try {
        $db = new PDO($dsn, $user, $password);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $db;
    } catch (PDOException $e) {
        exit('エラー：' . $e->getMessage());
    }
}

// 2. ログインID、パスワードを基にDBに登録されているユーザーか確認する
// @param string $userId ログインID
// @param string $userPw パスワード
// @return bool true: 登録ユーザー / false: 未登録

function isUser($userId, $userPw)
{
    $db = Connect();
    $stmt = $db->prepare('SELECT * FROM user WHERE loginId = :userId');
    $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch();

    // ユーザーが存在し、かつパスワードが一致するか確認する
    if ($user && $user['password'] === $userPw) {
        return true;
    }
    return false;
}

// 3. ログインID、パスワードを基にユーザー情報を取得し、連想配列で返却する
// @param string $userId ログインID
// @param string $userPw パスワード
// @return array|false array: 利用者情報 / false: DB未登録
function getUser($userId, $userPw)
{
    $db = Connect();
    $stmt = $db->prepare('SELECT * FROM user WHERE loginId = :userId');
    $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['password'] === $userPw) {
        return $user;
    }
    return false;
}

// 4. 引数を基に利用者情報をDBに登録する（ログインIDとパスワードはユニーク）
// @param string $viewName 表示名
// @param string $userId ログインID
// @param string $userPw パスワード
// @return bool true: 登録成功 / false: 登録失敗

function addUser($viewName, $userId, $userPw)
{
    $result = false;
    try {
        $db = Connect();
        $stmt = $db->prepare("
            INSERT INTO user (name, loginId, password) 
            VALUES (:viewName, :userId, :userPw)
        ");
        $stmt->bindValue(':viewName', $viewName, PDO::PARAM_STR);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
        $stmt->bindValue(':userPw', $userPw, PDO::PARAM_STR); // ハッシュ化せずそのまま
        $stmt->execute();
        $result = true;
    } catch (PDOException $e) {
        $result = false;
    }
    return $result;
}

// 5. DBから質問表示に必要な情報を2次元連想配列で取得する（新しい順）
// @return array|false

function getQuestion()
{
    $db = Connect();
    $stmt = $db->prepare("SELECT question.*, user.name AS userName 
        FROM question 
        JOIN user ON question.userId = user.id 
        WHERE question.deleteFlg = 0 
        ORDER BY question.date DESC");
    $stmt->execute();
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($questions)) {
        return false;
    }

    return $questions;
}

// 6. 登録時点の日時を取得し、質問をDBに登録する
// @param int $userId 質問者のID
// @param string $question 質問
// @return bool true: 登録成功

function addQuestion($userId, $question)
{
    $db = Connect();
    $stmt = $db->prepare('INSERT INTO question(userId ,question,date)VALUES(:userId,:question,NOW())');
    $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':question', $question, PDO::PARAM_STR);
    return $stmt->execute();
}

// 7. 質問IDに紐づく質問と回答を論理削除する（排他制御を意識）
// @param int $questionId 削除対象の質問ID
// @return bool true: 削除成功
function deleteQuestion($questionId)
{
    $db = Connect();
    try {
        $db->beginTransaction();

        // 質問の論理削除 (deleteFlgを1に)
        $stmt1 = $db->prepare('UPDATE question SET deleteFlg = 1 WHERE id = :questionId');
        $stmt1->bindValue(':questionId', $questionId, PDO::PARAM_INT);
        $stmt1->execute();

        // 質問に紐づく回答の論理削除
        $stmt2 = $db->prepare('UPDATE answer SET deleteFlg = 1 WHERE questionId = :questionId');
        $stmt2->bindValue(':questionId', $questionId, PDO::PARAM_INT);
        $stmt2->execute();
        $db->commit();
        return true;
    } catch (Exception $e) {
        $db->rollBack();
        return false;
    }
}

// 8. 質問IDに紐つく質問情報を取得する
// @param int $questionId 質問ID
// @return array|false

function getQuestionById($questionId)
{
    $db = Connect();
    $stmt = $db->prepare('SELECT question.*, user.name AS userName 
        FROM question 
        JOIN user ON question.userId = user.id 
        WHERE question.id = :questionId AND question.deleteFlg = 0');
    $stmt->bindValue(':questionId', $questionId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// 9. 質問IDに紐つく回答情報を取得する（日付の新しい順、回答番号順） 
// @param int $questionId 質問ID
// @return array|false

function getAnswersByQuestionId($questionId)
{
    $db = Connect();
    $stmt = $db->prepare('
        SELECT answer.*, user.name AS answererName 
        FROM answer 
        JOIN user ON answer.userId = user.id 
        WHERE answer.questionId = :questionId AND answer.deleteFlg = 0 
        ORDER BY answer.date DESC, answer.id ASC');
    $stmt->bindValue(':questionId', $questionId, PDO::PARAM_INT);
    $stmt->execute();
    $answers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($answers)) {
        return false;
    }
    return $answers;
}

// 10. 登録時点の日時を取得し、回答をDBに登録する
// @param int $userId 質問者のID（回答者ID）
// @param int $questionId 質問ID
// @param string $answer 回答
// @return bool true: 登録成功

function addAnswer($userId, $questionId, $answer)
{
    $db = Connect();
    $stmt = $db->prepare('INSERT INTO answer (questionId , userId, answer , date, deleteFlg) VALUES (:questionId, :userId, :answer, NOW(), 0)');
    $stmt->bindValue(':questionId', $questionId, PDO::PARAM_INT);
    $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':answer', $answer, PDO::PARAM_STR);

    return $stmt->execute();
}

// 11. 質問IDに紐づく質問内容をDBで更新する
// @param int $questionId 更新対象の質問ID
// @param string $question 更新後の質問内容
// @return bool true: 更新成功 / false: 更新失敗

function updateQuestion($questionId, $question)
{
    $db = Connect();
    $stmt = $db->prepare('UPDATE question SET question = :question WHERE id = :questionId');
    $stmt->bindValue(':question', $question, PDO::PARAM_STR);
    $stmt->bindValue(':questionId', $questionId, PDO::PARAM_INT);
    return $stmt->execute();
}

// 12. 質問IDに紐づく回答一覧をDBから取得する（該当なし・エラー時は空配列）
// @param int $questionId 取得対象の質問ID
// @return array

function getAnswers($questionId)
{
    $db = Connect();
    $stmt = $db->prepare('
    SELECT answer.*, user.name AS answererName 
        FROM answer 
        JOIN user ON answer.userId = user.id 
        WHERE answer.questionId = :questionId AND answer.deleteFlg = 0 
        ORDER BY answer.date DESC, id ASC');
    $stmt->bindValue(':questionId', $questionId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 13. 回答IDに紐つく回答をDBから論理削除する
// @param int $answerId 削除対象の回答ID
// @return bool true: 削除成功

function deleteAnswer($answerId)
{
    $db = Connect();
    $stmt = $db->prepare('UPDATE answer SET deleteFlg = 1 WHERE id = :answerId');
    $stmt->bindValue(':answerId', $answerId, PDO::PARAM_INT);
    return $stmt->execute();
}

// 以下、ログイン系の関数

// セッションを開始する（未開始の場合のみ）
function startSessionIfNeeded(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// ログイン中かどうかを判定する
// @return bool
function isLoggedIn(): bool
{
    startSessionIfNeeded();
    return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
}

// 未ログイン時にログイン画面へリダイレクトする（画面遷移図の制御用）
// @param string $page 遷移元識別子（'questionInput' や 'answer'）
// @param string $questionId 質問ID（回答画面からの遷移時など）
function requireLogin(string $page = '', string $questionId = ''): void
{
    if (!isLoggedIn()) {
        $params = [];
        if ($page !== '') {
            $params['page'] = $page;
        }
        if ($questionId !== '') {
            $params['questionId'] = $questionId;
        }
        $query = !empty($params) ? '?' . http_build_query($params) : '';
        header('Location: index.php' . $query);
        exit;
    }
}

// ログアウト処理を実行し、質問一覧画面へ遷移する（画面遷移図）
function logoutProcess(): void
{
    startSessionIfNeeded();
    $_SESSION = [];
    session_destroy();
    header('Location: questions.php');
    exit;
}

// ログイン認証と成功時のリダイレクト処理を行う（index.php用）
// @param string $userId
// @param string $userPw
// @param string $page
// @param string $questionId
// @return string 
function loginProcess(string $userId, string $userPw, string $page = '', string $questionId = ''): string
{
    startSessionIfNeeded();

    if ($userId === '' || $userPw === '') {
        return 'ユーザーIDとパスワードを入力してください。';
    }

    if (!isUser($userId, $userPw)) {
        return 'ユーザーIDまたはパスワードが間違っています。';
    }

    $user = getUser($userId, $userPw);
    if ($user === false) {
        return 'ユーザー情報の取得に失敗しました。';
    }

    $_SESSION['user'] = $user;

    // 画面遷移図に基づくログイン成功時の遷移先分岐（$pageで判定）
    if ($page === 'questionInput') {
        header('Location: questionInput.php');
        exit;
    } elseif ($page === 'answer' && $questionId !== '') {
        header('Location: answer.php?questionId=' . urlencode($questionId));
        exit;
    } else {
        header('Location: questions.php');
        exit;
    }
}