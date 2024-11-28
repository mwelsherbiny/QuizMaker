<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>QuizMaker</title>
    <link rel="stylesheet" href="/QuizMaker/Static/style.css">
    <script defer src="/QuizMaker/Static/nav.js"></script>
</head>
<body>
    <?php require_once "Static/nav.html"?>
    <div class="main">
        <div class="greeting">
            <h1>Welcome, <?php echo $fName ?></h1>
        </div>
        <a href='<?php echo $logOutUrl ?>'>
            <div class="auth">
                <div>Log out</div>
            </div>
        </a>
        <div class="cards">
            <a href="creations">
                <div class="created-quizzes-card">Created Quizzes</div>
            </a>
            <a href="submitted">
                <div class="submitted-quizzes-card">Submitted Quizzes</div>
            </a>
        </div>
    </div>
</body>
</html>