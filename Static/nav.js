let searchInput = document.querySelector(".search-input")
let searchIcon = document.querySelector(".search-icon")

searchInput.addEventListener("keypress", handleSearch)
searchIcon.addEventListener("click", search)

function handleSearch(event) {
    if (event.key === "Enter") {
        search()
    }
}

function search () {
    let searchQuery = document.querySelector(".search-input").value
    window.location = "/QuizMaker/search/" + searchQuery
}