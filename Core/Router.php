<?php

namespace Core;
use Controllers\Controller;
use Monolog\Handler\Curl\Util;

class Router
{
    private static $routes = [];

    static function addRoute($method, $uri, $handler) {
        Router::$routes[] = array("method" => $method, "uri" => $uri, "handler" => $handler);
    }

    static function handleCurrentRoute() {
        $currentUri = $_SERVER["REQUEST_URI"];
        $currentMethod = $_SERVER["REQUEST_METHOD"];
        $routeFound = false;
        $UriParams = null;
        $getParams = null;

        if (str_contains($currentUri, "search/")) {
            $UriParams = explode("search/", $currentUri)[1];
            $currentUri = "/QuizMaker/search/{name}";
        }

        if (str_contains($currentUri, "api/google_auth")) {
            $currentUri = "/QuizMaker/api/google_auth";
        }

        if (str_contains($currentUri, "api/quizzes?")) {
            // ?start=1&limit=30&search=math

            // ["start", "1&limit", "30&search", "math"]
            $getParamsArray = explode("=", $currentUri);

            $getParams["start"] = explode("&", $getParamsArray[1])[0];
            $getParams["limit"] = explode("&", $getParamsArray[2])[0];
            $getParams["search"] = $getParamsArray[3];

            $currentUri = "/QuizMaker/api/quizzes";
        }

        if (count(explode("api/quizzes_by_user/", $currentUri)) == 2) {
            $UriParams = explode("api/quizzes_by_user/", $currentUri)[1];
            $currentUri = "/QuizMaker/api/quizzes_by_user/{id}";
        }

        if (count(explode("attempt/", $currentUri)) == 2) {
            $UriParams = explode("attempt/", $currentUri)[1];
            $currentUri = "/QuizMaker/attempt/{id}";
        }

        if (count(explode("api/quiz/", $currentUri)) == 2) {
            $UriParams = explode("api/quiz/", $currentUri)[1];
            $currentUri = "/QuizMaker/api/quiz/{id}";
        }

        if (count(explode("api/users/", $currentUri)) == 2 && is_numeric(explode("api/users/", $currentUri)[1])) {
            $UriParams = explode("api/users/", $currentUri)[1];
            $currentUri = "/QuizMaker/api/users/{id}";
        }

        foreach (Router::$routes as $route) {
            if ($route["method"] == $currentMethod && $route["uri"] == $currentUri) {
                if (isset($getParams)) {
                    call_user_func($route["handler"], $getParams["start"], $getParams["limit"], $getParams["search"]);
                }
                else if (isset($UriParams)) {
                    call_user_func($route["handler"], $UriParams);
                }
                else {
                    call_user_func($route["handler"]);
                }
                $routeFound = true;
                break;
            }
        }

        if (!$routeFound) {
            Controller::handleError();
        }
    }
}