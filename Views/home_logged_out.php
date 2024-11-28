<?php
require "config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>QuizMaker</title>
    <link rel="stylesheet" href="/QuizMaker/Static/style.css">
    <script defer src="/QuizMaker/Static/nav.js"></script>
    <script defer src="/QuizMaker/Static/warning.js"></script>
</head>
<body>
    <?php require_once "Static/nav.html"?>
    <div class="main">
        <?php if ($invalidAction) require_once "Static/warning.html" ?>
        <div class="greeting">
            <h1>Welcome to QuizMaker</h1>
        </div>
        <a href='<?php echo $authUrl ?>'>
            <div class="auth">
                <img src="Static/images/google.png" alt="google logo">
                <div>Log in with Google</div>
            </div>
        </a>
    </div>
</body>
</html>