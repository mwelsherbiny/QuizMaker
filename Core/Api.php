<?php

namespace Core;

use Models\Quiz;

class Api
{
    public function addQuiz() {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (isset($data["name"]) && isset($data["questions"]) && isset($data["private"]))
        {
            $timeLimit = $data["time"] ?? null;
            $quiz = new Quiz($data["name"], $data["questions"], $data["private"], time(), $timeLimit);
        }
        else {
            http_response_code(405);
            echo "Failed to create quiz, please provide missing data";
        }

        $db = new Database();
        $quizId = $db->addQuiz($quiz);

        if ($quizId) {
            http_response_code(201);
            echo "Created quiz with ID " . $quizId;
        } else {
            http_response_code(405);
            echo "Failed to create quiz";
        }

    }

    public function getQuizzes($startId = null, $limit = null, $search = null) {
        $db = new Database();

        if ($search !== null) {
            $search = urldecode($search);
        }

        $quizzes = $db->getQuizzes($startId, $limit, $search);

        echo json_encode($quizzes);
    }

    public function getQuiz($quizId) {
        $db = new Database();
        $quiz = $db->getQuiz($quizId);

        if ($quiz === null) {
            echo "Quiz with ID " . $quizId . " doesn't exist";
        } else {
            echo json_encode($quiz);
        }
    }

    public function deleteQuiz($quizId) {
        $db = new Database();
        $resultMsg = $db->deleteQuiz($quizId);

        echo $resultMsg;
    }

    public function getUserData($userId) {
        $db = new Database();
        $user = $db->getUser($userId);

        echo json_encode($user);
    }

    public function getUserQuizzes($userId) {
        $db = new Database();
        $quizzesByUser = $db->GetQuizzesByUser($userId);

        echo json_encode($quizzesByUser);
    }
}