loadQuizData()
let questions = []
let questionsAnswers = []
let currentQuestionNumber = 1
let currentQuestionChoiceIndex = null
let timeDecrementInterval = null
let time = null
document.querySelector(".curr-choices-values").addEventListener("click", selectChoice)
document.querySelector(".quiz-info").addEventListener("click", switchQuestion)
document.querySelector(".submit-quiz-attempt-btn").addEventListener("click", submitAttempt)

function decrementTime() {
    time["seconds"]--
    if (time["seconds"] < 0) {
        time["minutes"]--
        time["seconds"] = 59
    }

    updateTime(time)

    if (time["seconds"] === 0 && time["minutes"] === 0) {
        document.querySelector(".submit-quiz-attempt-btn").click()
    }
}

function updateTime(time) {
    let minStr = time["minutes"] < 10? "0" + time["minutes"] : "" + time["minutes"]
    let secStr = time["seconds"] < 10? "0" + time["seconds"] : "" + time["seconds"]

    let timeElement = document.querySelector(".time-value")
    timeElement.innerText = minStr + ":" + secStr
}

async function getQuiz() {
    let quizId = window.location.href.split("attempt/")[1]

    let response = await fetch("/QuizMaker/api/quiz/" + quizId)
    return await response.json()
}

async function loadQuizData() {
    let quiz = await getQuiz()
    questions = quiz["questions"]
    for (let i = 0; i < questions.length; i++) {
        questionsAnswers.push({
            "questionNumber": i + 1,
            "answerIndex": null
        })
    }

    loadQuizMeta(quiz)
    loadQuizNav(questions)
    loadcurrentQuestion(questions[currentQuestionNumber - 1])
}

function loadQuizMeta(quiz) {
    document.querySelector(".quiz-name-value").innerText = quiz.name

    if (quiz.time !== null) {
        time = {"minutes": quiz.time, "seconds": 0}
        let timeStr = quiz.time < 10? "0" + quiz.time + ":00" : "" + quiz.time + ":00"

        document.querySelector(".time-value").innerText = timeStr

        timeDecrementInterval = window.setInterval(decrementTime, 1000)
    }
}

function selectChoice(event) {
    if (event.target.className === "choice-value") {
        unselectLastChoice(currentQuestionChoiceIndex)
        currentQuestionChoiceIndex = event.target.id.split("choice-")[1]
        updateSelected(currentQuestionChoiceIndex)
    }
}

function unselectLastChoice(questionChoice) {
    if (questionChoice !== null) {
        let selectedChoice = document.querySelector("#choice-" + questionChoice)
        selectedChoice.style.backgroundColor = "white"
        selectedChoice.style.color = "#2c3f47"
    }
}

function updateSelected(questionChoice) {
    if (questionChoice !== null) {
        let selectedChoice = document.querySelector("#choice-" + questionChoice)
        selectedChoice.style.backgroundColor = "#009DFF"
        selectedChoice.style.color = "white"
    }
}

function loadQuizNav(questions) {
    let quizNavList = document.querySelector(".quiz-info")

    for (let i = 0; i < questions.length; i++) {
        let markerNumber = i + 1

        let quizNavMarker = document.createElement("div")
        quizNavMarker.setAttribute("class", "question-marker clickable no-select")
        quizNavMarker.setAttribute("id", "marker-" +  markerNumber)
        quizNavMarker.innerText = markerNumber.toString()

        quizNavList.appendChild(quizNavMarker)
    }

    quizNavList.children[0].style.backgroundColor = "#009DFF"
}

function loadcurrentQuestion(question) {
    document.querySelector(".curr-question-value").innerText = question.body

    let choices = document.getElementsByClassName("choice-value")

    for (let i = 0; i < choices.length; i++) {
        choices[i].innerText = question.answers[i]
    }
}

function switchQuestion(event) {
    if (event.target.classList[0] === "question-marker") {
        let newQuestionNumber = parseInt(event.target.id.split("marker-")[1])
        handleSwitch(newQuestionNumber)
    }
}

function handleSwitch(newQuestionNumber) {
    if (currentQuestionNumber !== newQuestionNumber) {
        let prevQuestionNumber = currentQuestionNumber
        let prevChoiceIndex = currentQuestionChoiceIndex
        currentQuestionNumber = newQuestionNumber

        unselectQuestion(prevQuestionNumber)
        selectQuestion(currentQuestionNumber)

        questionsAnswers[prevQuestionNumber - 1] = {
            "questionNumber": prevQuestionNumber,
            "answerIndex": prevChoiceIndex === null? null : parseInt(prevChoiceIndex)
        }

        unselectLastChoice(prevChoiceIndex)

        loadcurrentQuestion(questions[newQuestionNumber - 1])
        setNewQuestionAnswer(questionsAnswers)
        updateSelected(currentQuestionChoiceIndex)
    }
}

function setNewQuestionAnswer(questionsAnswers) {
    let foundAnswer = questionsAnswers.find(questionAnswers => questionAnswers.questionNumber === currentQuestionNumber)
    if (foundAnswer) {
        currentQuestionChoiceIndex = foundAnswer.answerIndex
    }
    else {
        currentQuestionChoiceIndex = null
    }
}

function selectQuestion(selectedQuestion) {
    let currentQuestionMarker = document.querySelector("#marker-" + selectedQuestion)
    currentQuestionMarker.style.backgroundColor = "#009DFF"
}

function unselectQuestion(unselectedQuestion) {
    let currentQuestionMarker = document.querySelector("#marker-" + unselectedQuestion)
    currentQuestionMarker.style.backgroundColor = "#939BB4"
}

function submitAttempt(event) {
    clearInterval(timeDecrementInterval)

    disableClicks()
    document.querySelector(".quiz-info").removeEventListener("click", switchQuestion)
    document.querySelector(".quiz-info").addEventListener("click", displayQuestionAnswer)

    registerLastAnswer()

    questionsAnswers.sort((q1, q2) => q1.questionNumber - q2.questionNumber)

    fillQuestionAnswers()

    let score = gradeQuiz()
    displayScore(score, questions.length)

    showNextQuestionAnswer(currentQuestionNumber)
}

function displayQuestionAnswer(event) {
    setChoiceColor(currentQuestionChoiceIndex, "white", "#2c3f47")

    let newQuestionNumber = parseInt(event.target.id.split("marker-")[1])
    loadcurrentQuestion(questions[newQuestionNumber - 1])


    if (event.target.classList[0] === "question-marker") {
        showNextQuestionAnswer(newQuestionNumber)

        currentQuestionNumber = newQuestionNumber
    }
}

function showNextQuestionAnswer(newQuestionNumber) {
    let questionIndex = newQuestionNumber - 1
    let correctChoice = questions[questionIndex].answerIndex

    setChoiceColor(correctChoice, "#45cc2d")

    currentQuestionChoiceIndex = correctChoice
}

function setChoiceColor(choiceIndex, bgColor, color="white") {
    document.querySelector("#choice-" + choiceIndex).style.backgroundColor = bgColor
    document.querySelector("#choice-" + choiceIndex).style.color = color
}

function displayScore(score, maxScore) {
    let scoreDiv =  document.querySelector(".score")
    scoreDiv.innerText = "Result: " + score + "/" + questions.length
    console.log(score / maxScore)
    if (score / maxScore < 0.5) {
        scoreDiv.style.backgroundColor = "#ffcccc"
    }
    else {
        scoreDiv.style.backgroundColor = "#ccffcc"
    }
    scoreDiv.style.display = "block"
}

function gradeQuiz() {
    let score = 0;
    let maxScore = questions.length

    for (let i = 0; i < maxScore; i++) {
        if (questions[i].answerIndex === questionsAnswers[i].answerIndex) {
            score++
            setMarkerColor(i + 1, "#45cc2d")
        }
        else {
            setMarkerColor(i + 1, "#ff0f0f")
        }
    }

    return score
}

function registerLastAnswer() {
    questionsAnswers[currentQuestionNumber - 1] = {
        "questionNumber": currentQuestionNumber,
        "answerIndex": currentQuestionChoiceIndex === null? null : parseInt(currentQuestionChoiceIndex)
    }
}

function setMarkerColor(markerNumber, colorCode) {
    document.querySelector("#marker-" + markerNumber).style.backgroundColor = colorCode
}

function fillQuestionAnswers() {
    for (let i = 0; i < questions.length; i++) {
        if (!questionsAnswers[i]) {
            questionsAnswers[i] = {
                "questionNumber": i + 1,
                "answerIndex": NaN
            }
        }
    }
}

function disableClicks() {
    document.querySelector(".curr-choices-values").style.pointerEvents = "none"
    document.querySelector(".submit-quiz-attempt-btn").style.pointerEvents = "none"
}