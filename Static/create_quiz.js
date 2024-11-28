let questionsN = 1
let currentQuestionNumber = 1;
let currentQuestionCorrectAnswerIndex = null;
let questions = [{
    "order": currentQuestionNumber,
    "body": getCurrentQuestionBody(),
    "answers": getPossibleAnswers(),
    "answerIndex": currentQuestionCorrectAnswerIndex
}]
let warningMsg = ""

document.querySelector(".question-nav").addEventListener("click", handleNavigation)
let checkMarks = document.getElementsByClassName("correct-answer-marker")
for (let i = 0; i < checkMarks.length; i++) {
    checkMarks[i].addEventListener("click", setCorrectAnswer)
}
document.querySelector(".main-form").addEventListener("submit", handleQuizSubmission)


function handleNavigation(event) {
    if (event.target.classList[0] === "question-marker") {
        let clickedQuestion = event.target
        let selectedQuestionNumber = parseInt(clickedQuestion.innerText);
        if (selectedQuestionNumber !== currentQuestionNumber) {
            saveCurrentQuestion()
            switchQuestion(selectedQuestionNumber)
            currentQuestionNumber = selectedQuestionNumber
        }
    }
    else if (event.target.classList[0] === "add-question-btn" && questionsN < 50) {
        questionsN++

        let questionMarkersList = document.querySelector(".quiz-info")

        let newQuestionMarker = document.createElement("div")
        newQuestionMarker.classList.add("question-marker" ,"clickable", "no-select")
        newQuestionMarker.innerText = questionsN.toString()

        questionMarkersList.append(newQuestionMarker)
        questions.push({
            "order": questionsN,
            "body": "",
            "answers": ["", "", "", ""],
            "answerIndex": null
        })
    }
    else if (event.target.classList[0] === "remove-question-btn" && questionsN > 1) {
        let removedQuestionIndex =  questions.findIndex(question => question.order === questionsN)

        if (removedQuestionIndex !== -1) {
            questions.splice(removedQuestionIndex, 1)
        }

        let questionMarkers = document.getElementsByClassName("question-marker")
        let questionMarkersList = document.querySelector(".quiz-info")

        for (let i = 0; i < questionMarkers.length; i++) {
            if (parseInt(questionMarkers[i].innerText) === questionsN) {
                questionMarkersList.removeChild(questionMarkers[i])
            }
        }

        if (questionsN === currentQuestionNumber) {
            currentQuestionNumber--
            switchQuestion(currentQuestionNumber)
            questionMarkers[currentQuestionNumber - 1].style.backgroundColor = "#009DFF"
        }

        questionsN--
    }
}

function saveCurrentQuestion() {

    let questionIndex = questions.findIndex(question => question.order === currentQuestionNumber)

    if (questionIndex !== -1) {
        questions[questionIndex] = {
            "order": currentQuestionNumber,
            "body": getCurrentQuestionBody(),
            "answers": getPossibleAnswers(),
            "answerIndex": currentQuestionCorrectAnswerIndex
        }
    }
    else {
        questions.push(
            {
                "order": currentQuestionNumber,
                "body": getCurrentQuestionBody(),
                "answers": getPossibleAnswers(),
                "answerIndex": currentQuestionCorrectAnswerIndex
            }
        )
    }
}

function getCurrentQuestionBody() {
    let currentQuestionElement = document.querySelector(".curr-question")
    return currentQuestionElement.value;
}

function getPossibleAnswers() {
    let choicesInputs = document.getElementsByClassName("choice-input")

    let choices = []
    for (let i = 0; i < choicesInputs.length; i++) {
        choices.push(choicesInputs[i].value)
    }

    return choices
}

function setCorrectAnswer(event) {
    let clickedCheckmark = event.target

    if (currentQuestionCorrectAnswerIndex !== null) {
        document.querySelector("#choice-" + currentQuestionCorrectAnswerIndex).style.backgroundColor = "#939BB4"
    }

    currentQuestionCorrectAnswerIndex = parseInt(clickedCheckmark.id.split("choice-")[1])
    clickedCheckmark.style.backgroundColor = "#45cc2d"
}

function switchQuestion(newQuestionNumber) {
    let newQuestion = questions.find(question => question.order === newQuestionNumber)

    let questionInput = document.querySelector(".curr-question")
    let choicesInput = document.getElementsByClassName("choice-input")

    if (currentQuestionCorrectAnswerIndex !== null) {
        let correctAnswerCheckmark = document.querySelector("#choice-" + currentQuestionCorrectAnswerIndex)
        correctAnswerCheckmark.style.backgroundColor = "#939BB4"
    }

    if (newQuestion) {
        questionInput.value = newQuestion.body

        for (let i = 0; i < choicesInput.length; i++) {
            choicesInput[i].value = newQuestion["answers"][i]
        }

        currentQuestionCorrectAnswerIndex = newQuestion["answerIndex"]

        if (currentQuestionCorrectAnswerIndex !== null) {
            document.querySelector("#choice-" + currentQuestionCorrectAnswerIndex).style.backgroundColor = "#45cc2d"
        }
    }
    else {
        questionInput.value = ""

        for (let i = 0; i < choicesInput.length; i++) {
            choicesInput[i].value = ""
        }

        currentQuestionCorrectAnswerIndex = null
    }

    markCurrentQuestion(newQuestionNumber)
}

function markCurrentQuestion(newQuestionNumber) {
    let questionMarkers = document.getElementsByClassName("question-marker")

    for (let i = 0; i < questionMarkers.length; i++) {
        if (parseInt(questionMarkers[i].innerText) === currentQuestionNumber) {
            questionMarkers[i].style.backgroundColor = "#939BB4"
        }
        else if (parseInt(questionMarkers[i].innerText) === newQuestionNumber) {
            questionMarkers[i].style.backgroundColor = "#009DFF"
        }
    }
}

function handleQuizSubmission(event) {
    event.preventDefault()

    let quizName = document.querySelector("#quiz-name").value.trim()
    let quizTime = document.querySelector("#quiz-time").value === ""? null : parseInt(document.querySelector("#quiz-time").value)
    let visibilityChoices = document.getElementsByName("visibility")
    saveCurrentQuestion()

    if (!isInputValid(quizName, quizTime, visibilityChoices)) {
        document.querySelector(".form-warning").children[0].innerText = warningMsg
        document.querySelector(".form-warning").children[0].style.display = "block"
        return;
    }

    submitQuiz(quizName, quizTime, getSelectedVisibility(visibilityChoices))
}

function isInputValid(quizName, quizTime, visibilityChoices) {
    if (questionsN !== questions.length) {
        warningMsg = "Please fill all questions"
        return false
    }

    if (quizName.length === 0) {
        warningMsg = "Please enter the quiz's name"
        return false
    }
    if (isNaN(quizTime)) {
        warningMsg = "Please enter a valid quiz time"
        return false
    }
    if (getSelectedVisibility(visibilityChoices) === null) {
        warningMsg = "Please specify the quiz's visibility"
        return false
    }
    return areQuestionsFilled();
}

function getSelectedVisibility(visibilityChoices) {
    let selectedOption = null

    for (let i = 0; i < visibilityChoices.length; i++) {
        if (visibilityChoices[i].checked) {
            selectedOption = visibilityChoices[i].value
        }
    }

    return selectedOption
}

function areQuestionsFilled() {
    for (let i = 0; i < questions.length; i++) {
        let currentQuestion = questions[i]
        if (currentQuestion.body.length === 0) {
            warningMsg = "Please enter question " + currentQuestion.order +  " text"
            return false
        }
        if (currentQuestion.answerIndex === null) {
            warningMsg = "Please specify the correct choice for question " + currentQuestion.order
            return false
        }
        if (!areAnswersFilled(currentQuestion.answers, currentQuestion.order) || !areAnswersDifferent(currentQuestion.answers, currentQuestion.order)) {
            return false
        }
    }
    return true
}

function areAnswersFilled(answers, questionOrder) {
    for (let i = 0; i < answers.length; i++) {
        if (answers[i].trim().length === 0) {
            warningMsg = "Please enter all choices for question " + questionOrder
            return false
        }
    }
    return true
}

function areAnswersDifferent(answers, questionOrder) {
    for (let i = 0; i < answers.length; i++) {
        let currAnswer = answers[i].trim()
        for (let j = 0; j < answers.length; j++) {
            if (j !== i && currAnswer === answers[j].trim()) {
                warningMsg = "Please ensure all choices are different for question " + questionOrder
                return false
            }
        }
    }
    return true
}

async function submitQuiz(quizName, quizTime, visibility) {
    let quiz = {
        "name": quizName,
        "questions" : questions.map(({body, answers, answerIndex}) => ({body, answers, answerIndex})),
        "private": visibility === "private",
        "time": quizTime
    }

    try {
        let response = await fetch("/QuizMaker/api/quizzes", {
            method: "POST",
            body: JSON.stringify(quiz)
        })

        if (!response.ok) {
            throw new Error("Unable to submit quiz")
        }

        let responseJson = await response.text()
        console.log(responseJson)
    }
    catch (error) {
        console.log(error.message)
    }

    window.location = "/QuizMaker"
}