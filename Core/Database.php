<?php

namespace Core;

require "vendor/autoload.php";

use Dotenv\Dotenv;
use PDO;

require "config.php";

class Database
{
    private $pdo;
    private $googleId;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();

        $dbUser = $_ENV["DB_USER"];
        $dbHost = $_ENV["DB_HOST"];
        $dbName = $_ENV["DB_NAME"];
        $dbPass = $_ENV["DB_PWD"];

        $this->pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);

        global $gClient;
        $this->googleId = $_SESSION["googleId"] ?? null;
    }

    /**
     * @param mixed $googleId
     */
    public function setGoogleId(mixed $googleId): void
    {
        $this->googleId = $googleId;
    }

    public function isUserSignedUp($googleId) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE google_id=?");
        $stmt->execute([$googleId]);

        return $stmt->fetchColumn() !== false;
    }

    public function signUpUser($name, $email, $googleId) {
        $stmt = $this->pdo->prepare("INSERT INTO users (google_id, name, attempted_quizzes, created_quizzes, email) 
            VALUES (?, ?, ?, ?, ?)");

        $stmt->execute([$googleId, $name, 0, 0, $email]);
    }

    public function getUserName() {
        $stmt = $this->pdo->prepare("SELECT name FROM users WHERE google_id=?");
        $stmt->execute([$this->googleId]);
        return $stmt->fetch(PDO::FETCH_ASSOC)["name"];
    }

    public function getUserID() {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE google_id=?");
        $stmt->execute([$this->googleId]);
        return $stmt->fetch(PDO::FETCH_ASSOC)["id"];
    }

    public function addQuiz($quiz) {
        $stmt = $this->pdo->prepare("INSERT INTO quizzes (user_id, name, questions, private, time, questions_count)
                VALUES (?, ?, ?, ?, ?, ?)");
        $isSuccessful = $stmt->execute([$this->getUserID(), ucwords($quiz->getName()), json_encode($quiz->getQuestions()), $quiz->isPrivate(), $quiz->getTime(), $quiz->getQuestionsCount()]);

        if ($isSuccessful) {
            $quizId = $this->pdo->lastInsertId();
            $this->countQuizCreation($this->getUserID());
            return $quizId;
        }
        else {
            return null;
        }
    }

    private function countQuizCreation($userId) {
        $stmt = $this->pdo->prepare("UPDATE users SET created_quizzes = created_quizzes + 1
                WHERE id=?");
        $stmt->execute([$userId]);
    }

    public function getQuizzes($startId, $limit, $search) {
        if ($limit === null) {
            $stmt = $this->pdo->prepare("SELECT id, user_id, name, created_at, private, time, questions_count FROM quizzes WHERE private=0 ORDER BY id");
            $stmt->execute([]);
        } else {
            $query = "SELECT id, user_id, name, created_at, private, time, questions_count FROM quizzes WHERE id > ? AND name LIKE ? AND private=0 ORDER BY id LIMIT $limit";
            $stmt = $this->pdo->prepare($query);
            $search = "%" . $search . "%";
            $stmt->execute([$startId, $search]);
        }

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($result) == 0 || !$result) {
            return [];
        }

        return $result;
    }

    public function getQuiz($quizId) {
        $stmt = $this->pdo->prepare("SELECT * FROM quizzes WHERE id=?");
        $stmt->execute([$quizId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() === 0) {
            return null;
        }

        $result['questions'] = json_decode($result['questions'], true);

        return $result;
    }

    public function deleteQuiz($quizId) {
        $stmt = $this->pdo->prepare("DELETE FROM quizzes WHERE id=?");
        if ($stmt->execute([$quizId])) {
            if ($stmt->rowCount() > 0) {
                $this->countQuizDeletion($this->getUserID());
                return "Deleted quiz with ID " . $quizId;
            }
            else {
                return "Quiz with ID " . $quizId . " not found";
            }
        }
        else {
            return "Failed to delete quiz";
        }
    }

    public function countQuizDeletion($userId) {
        $stmt = $this->pdo->prepare("UPDATE users SET created_quizzes = created_quizzes - 1 
                WHERE id=?");
        $stmt->execute([$userId]);
    }

    public function getUser($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function GetQuizzesByUser($userId) {
        $stmt = $this->pdo->prepare("SELECT id, name, created_at, private, time, questions_count FROM quizzes WHERE user_id=? ORDER BY id DESC");
        $stmt->execute([$userId]);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($result) == 0 || !$result) {
            return [];
        }

        return $result;
    }
}