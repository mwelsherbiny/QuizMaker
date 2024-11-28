loadQuizzes()

async function loadQuizzes() {
    let response = await fetch("/QuizMaker/api/quizzes_by_user/" + getCookie("userId") , {
        method: "GET"
    })
    let quizzes = await response.json()

    let quizList = document.querySelector(".quiz-cards")

    for (let i = 0; i < quizzes.length; i++) {
        let quiz = quizzes[i]

        displayQuiz(quiz, quizList)
    }
}

function getCookie(name) {
    function escape(s) { return s.replace(/([.*+?\^$(){}|\[\]\/\\])/g, '\\$1'); }
    let match = document.cookie.match(RegExp('(?:^|;\\s*)' + escape(name) + '=([^;]*)'));
    return match ? match[1] : null;
}