<?php
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>QuizMaker</title>
    <link rel="stylesheet" href="/QuizMaker/Static/style.css">
    <script defer src="/QuizMaker/Static/nav.js"></script>
    <script defer src="/QuizMaker/Static/create_quiz.js"></script>
</head>
<body>
    <?php require_once "Static/nav.html"?>
    <div class="main">
        <form class="main-form">
            <div class="name-form">
                <label for="quiz-name">Quiz Name </label>
                <input id="quiz-name" type="text" placeholder="Enter quiz name">
            </div>
            <div class="visibility-form">
                <span>Visibility</span>
                <input name="visibility" type="radio" id="public" value="public">
                <label for="public">Public</label>
                <input name="visibility" type="radio" id="private" value="private">
                <label for="private">Private</label>
            </div>
            <div class="time-form">
                <label for="quiz-time">Quiz Time </label>
                <input id="quiz-time" type="number" placeholder="Enter time in minutes">
                <span>Leave empty to disable time limit</span>
            </div>
            <div class="submit-quiz">
                <input class="clickable" type="submit" value="Create Quiz">
            </div>
            <div class="form-warning">
                <div>Please enter all data</div>
            </div>
        </form>
        <div class="question-form">
            <div class="curr-question-form">
                <textarea class="curr-question" placeholder="Enter question"></textarea>
                <div class="curr-choices">
                    <div>
                        <input class="choice-input" type="text" placeholder="Enter choice">
                        <span id="choice-0" class="correct-answer-marker clickable no-select">✔</span>
                    </div>
                    <div>
                        <input class="choice-input" type="text" placeholder="Enter choice">
                        <span id="choice-1" class="correct-answer-marker clickable no-select">✔</span>
                    </div>
                    <div>
                        <input class="choice-input" type="text" placeholder="Enter choice">
                        <span id="choice-2" class="correct-answer-marker clickable no-select">✔</span>
                    </div>
                    <div>
                        <input class="choice-input" type="text" placeholder="Enter choice">
                        <span id="choice-3" class="correct-answer-marker clickable no-select">✔</span>
                    </div>
                </div>
            </div>
            <div class="question-nav">
                <div class="edit-quiz">
                    <div class="add-question-btn clickable no-select">+</div>
                    <div class="remove-question-btn clickable no-select">-</div>
                </div>
                <div class="quiz-info">
                    <div class="question-marker clickable no-select" style="background-color: #009DFF">1</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
