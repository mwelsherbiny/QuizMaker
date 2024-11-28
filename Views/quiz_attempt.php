<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>QuizMaker</title>
    <link rel="stylesheet" href="/QuizMaker/Static/style.css">
    <script defer src="/QuizMaker/Static/display_quiz.js"></script>
    <script defer src="/QuizMaker/Static/nav.js"></script>
    <script defer src="/QuizMaker/Static/attempt_quiz.js"></script>
</head>
<body>
<?php require_once "Static/nav.html"?>
    <div class="main">
        <div class="quiz-data">
            <div class="quiz-name-value"></div>
            <div class="time-value"></div>
            <div class="submit-quiz-attempt">
                <input class="submit-quiz-attempt-btn clickable" type="button" value="Submit">
            </div>
            <div class="score"></div>
        </div>

        <div class="question-form">
            <div class="curr-question-data">
                <div class="curr-question-value"></div>
                <div class="curr-choices-values">
                    <div id="choice-0" class="choice-value"></div>
                    <div id="choice-1" class="choice-value"></div>
                    <div id="choice-2" class="choice-value"></div>
                    <div id="choice-3" class="choice-value"></div>
                </div>
            </div>
            <div class="question-nav">
                <div class="quiz-info">
                </div>
            </div>
        </div>
    </div>
</body>
</html>