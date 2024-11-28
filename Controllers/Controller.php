<?php
namespace Controllers;

require "config.php";

use Core\Database;
use Core\Utility;
use Google_Service_Oauth2;
use Monolog\Handler\Curl\Util;

class Controller
{
    private $loggedIn;
    private $gClient;
    private $invalidAction;

    public function __construct()
    {
        global $gClient;

        $this->gClient = $gClient;
        $this->loggedIn = isset($_COOKIE['loggedIn']) && $_COOKIE['loggedIn'] === 'true';

        session_start();
        if (!isset($_SESSION["googleId"])) {
            setcookie('loggedIn', 'false', time() - 3600, '/');
            setcookie('userId', '', time() - 3600, '/');
            $this->loggedIn = false;
        }
        $this->invalidAction = $_SESSION["invalidAction"] ?? false;
        $_SESSION["invalidAction"] = false;
    }

    static function handleError() {
        require_once "Static/route_not_found.html";
    }

    public function getHomeView() {
        $invalidAction = $this->invalidAction;

        if ($this->loggedIn) {
            $db = new Database();
            $fName = explode(" ", $db->getUserName())[0];

            $logOutUrl = "/QuizMaker/api/log_out";

            require_once "Views/home_logged_in.php";
        }
        else {
            require_once "Views/home_logged_out.php";
        }
    }

    public function googleAuth() {
        if (isset($_GET["error"])) {
            setcookie('loggedIn', 'false', time() - 3600, '/');
            setcookie('userId', '', time() - 3600, '/');
        }
        else if (isset($_GET["code"])) {
            $accessToken = $this->gClient->fetchAccessTokenWithAuthCode($_GET["code"]);

            $this->gClient->setAccessToken($accessToken);

            $oauth2Service = new Google_Service_Oauth2($this->gClient);
            $googleUserInfo = $oauth2Service->userinfo->get();

            $googleUserId = $googleUserInfo->id;
            $googleUsername = $googleUserInfo->name;
            $googleEmail = $googleUserInfo->email;

            $_SESSION["googleId"] = $googleUserId;

            $db = new Database();

            if (!$db->isUserSignedUp($googleUserId)) {
                $db->signUpUser($googleUsername, $googleEmail, $googleUserId);
            }

            setcookie('loggedIn', 'true', time() + 3600 * 24 * 30, '/');
            setcookie('userId', $db->getUserId(), time() + 3600 * 24 * 30, '/');
        }

        header("Location: /QuizMaker");
    }

    public function logOut() {
        setcookie('loggedIn', 'false', time() - 3600, '/');
        setcookie('userId', '', time() - 3600, '/');
        header("Location: /QuizMaker");
    }

    public function getCreateView() {
        if (isset($_COOKIE['loggedIn']) && $_COOKIE['loggedIn'] === 'true') {
            $_SESSION["invalidAction"] = false;
            require_once "Views/create_quiz.php";
        }
        else {
            $_SESSION["invalidAction"] = true;
            header("Location: /QuizMaker");
        }
    }

    public function getCreationsView() {
        if (isset($_COOKIE['loggedIn']) && $_COOKIE['loggedIn'] === 'true') {
            $_SESSION["invalidAction"] = false;
            require_once "Views/creations.php";
        }
        else {
            $_SESSION["invalidAction"] = true;
            header("Location: /QuizMaker");
        }
    }

    public function searchQuizView() {
        require_once "Views/quiz_search.php";
    }

    public function attemptQuizView() {
        require_once "Views/quiz_attempt.php";
    }
}
