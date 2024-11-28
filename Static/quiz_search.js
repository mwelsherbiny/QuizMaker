let limit = Math.ceil(20 * window.innerHeight / 500)
let searchStr = window.location.href.split("search/")[1]
let startId = 1
let allLoaded = false;

window.addEventListener("scroll", loadIfRequired)

if (!allLoaded) {
    loadQuizzes(limit)
}

function loadIfRequired() {
    let scrollOffset = window.scrollY
    let visibleWindowHeight = window.innerHeight
    let totalHeight = document.documentElement.scrollHeight

    if (scrollOffset + visibleWindowHeight >= totalHeight) {
        loadQuizzes(limit)
    }
}

async function loadQuizzes(limit) {


    let response = await fetch(`/QuizMaker/api/quizzes?start=${startId}&limit=${limit}&search=${searchStr}` , {
        method: "GET"
    })
    let quizzes = await response.json()

    if (quizzes.length === 0) {
        allLoaded = true
        return;
    }

    let quizList = document.querySelector(".quiz-cards")

    for (let i = 0; i < quizzes.length; i++) {
        let quiz = quizzes[i]

        displayQuiz(quiz, quizList)
    }

    startId = quizzes[quizzes.length - 1].id
}
