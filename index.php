<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once "config.php";

use Core\Api;
use Core\Router;
use Controllers\Controller;

$controller = new Controller();
$api = new Api();
$startingPath = "/QuizMaker/";

// Controller Router
Router::addRoute("GET", $startingPath, [$controller, "getHomeView"]);
Router::addRoute("GET", $startingPath . "api/google_auth", [$controller, "googleAuth"]);
Router::addRoute("GET", $startingPath . "api/log_out", [$controller, "logOut"]);
Router::addRoute("GET", $startingPath . "create", [$controller, "getCreateView"]);
Router::addRoute("GET", $startingPath . "creations", [$controller, "getCreationsView"]);
Router::addRoute("GET", $startingPath . "search/{name}", [$controller, "searchQuizView"]);
Router::addRoute("GET", $startingPath . "attempt/{id}", [$controller, "attemptQuizView"]);

// API Router
Router::addRoute("POST", $startingPath . "api/quizzes", [$api, "addQuiz"]);
Router::addRoute("GET", $startingPath . "api/quizzes", [$api, "getQuizzes"]);
Router::addRoute("GET", $startingPath . "api/quiz/{id}", [$api, "getQuiz"]);
Router::addRoute("DELETE", $startingPath . "api/quiz/{id}", [$api, "deleteQuiz"]);
Router::addRoute("GET", $startingPath . "api/users/{id}", [$api, "getUserData"]);
Router::addRoute("GET", $startingPath . "api/quizzes_by_user/{id}", [$api, "getUserQuizzes"]);

Router::handleCurrentRoute();


