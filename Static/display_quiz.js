function handleAttempt(event) {
    let id = event.target.getAttribute("id").split("-")[1]
    window.location = "/QuizMaker/attempt/" + id
}

function displayQuiz(quiz, quizList) {
    let quizCard = document.createElement("div")
    quizCard.setAttribute("class", "quiz-card")
    quizCard.setAttribute("id", "quiz-" + quiz.id)

    let quizName = document.createElement("div")
    quizName.setAttribute("class", "quiz-name")
    quizName.innerText = quiz["name"]

    let quizNumberOfQuestions = document.createElement("div")
    quizNumberOfQuestions.setAttribute("class", "number-of-questions")
    if (quiz["questions_count"] === 1) {
        quizNumberOfQuestions.innerText = quiz["questions_count"] + " question"
    }
    else {
        quizNumberOfQuestions.innerText = quiz["questions_count"] + " questions"
    }

    let quizTime = document.createElement("div")
    quizTime.setAttribute("class", "quiz-time")
    if (quiz["time"] === null) {
        quizTime.innerText = "No time limit"
    }
    else {
        quizTime.innerText = quiz["time"] + " min"
    }

    let quizAttempt = document.createElement("div")
    quizAttempt.setAttribute("class", "attempt-quiz")
    let quizAttemptButton = document.createElement("input")
    quizAttemptButton.setAttribute("type", "button")
    quizAttemptButton.setAttribute("class", "attempt-quiz-btn clickable")
    quizAttemptButton.setAttribute("id", "attempt-" + quiz.id)
    quizAttemptButton.value = "Attempt"
    quizAttemptButton.addEventListener("click", handleAttempt)
    quizAttempt.appendChild(quizAttemptButton)

    quizCard.appendChild(quizName)
    quizCard.appendChild(quizNumberOfQuestions)
    quizCard.appendChild(quizTime)
    quizCard.appendChild(quizAttempt)

    quizList.appendChild(quizCard)
}