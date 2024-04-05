window.onload = function () {
    updatePlayTime();
    updatePlayerCount();
    const savedRecommendedGames = getSavedRecommendedGames();
    if (savedRecommendedGames.length > 0) {
        displayRecommendedGames(savedRecommendedGames);
    }
    const savedFormValues = getSavedFormValues();
    if (savedFormValues) {
        restoreFormValues(savedFormValues);
    }
};

function getRecommendedGames() {
    const selectedGenre = document.getElementById("genre").value;
    const selectedAge = parseInt(document.getElementById("age").value);
    const selectedPlayers = parseInt(document.getElementById("players").value);
    const selectedTime = parseInt(document.getElementById("time").value);
    const selectedPubl = parseInt(document.getElementById("publ").value);
    console.log(selectedGenre);
    var toastBody = document.getElementById('toast');
    const toast = new bootstrap.Toast(toastBody);
    var toastText = document.getElementById("toastText");
    $.ajax({
        type: "POST",
        url: "source/php/recom.php",
        data: { genre: selectedGenre, age: selectedAge, players: selectedPlayers, time: selectedTime, publ: selectedPubl },
        dataType: "json",
        error: function (xhr, status, error) {
            toastText.innerText = xhr.responseJSON.message;
            toastBody.classList.add('fail');
            toast.show();
            setTimeout(function () {
                toastBody.classList.remove('fail');
                toast.hide();
            }, 1500);
        },
        success: function (data) {
            console.log(data);
            const resultElement = document.getElementById("result");
            if (data.length > 0) {
                saveRecommendedGames(data);
                displayRecommendedGames(data);
                saveFormValues();
            } else {
                resultElement.textContent = "Игры не найдены. Попробуйте изменить параметры.";
            }
        }
    }); 
}

function displayRecommendedGames(recommendedGames) {
    const resultElement = document.getElementById("result");
    resultElement.innerHTML = "<p>Рекомендуемые игры:</p>";
    recommendedGames.forEach(game => {
        const button = document.createElement("button");
        button.className = "game-button";
        button.textContent = game.title;
        button.setAttribute("data-game-id", game.id);
        button.addEventListener("click", function(event) {
            event.preventDefault();
            const gameId = this.getAttribute("data-game-id");
            window.location.href = `pattern.html?game=${gameId}`;
        });

        resultElement.appendChild(button);
    });
}

function saveRecommendedGames(recommendedGames) {
    sessionStorage.setItem("recommendedGames", JSON.stringify(recommendedGames));
}

function getSavedRecommendedGames() {
    const savedRecommendedGames = sessionStorage.getItem("recommendedGames");
    return savedRecommendedGames ? JSON.parse(savedRecommendedGames) : [];
}

function restoreFormValues(savedFormValues) {
    document.getElementById("genre").value = savedFormValues.genre;
    document.getElementById("age").value = savedFormValues.age;
    document.getElementById("players").value = savedFormValues.players;
    document.getElementById("time").value = savedFormValues.time;
    document.getElementById("publ").value = savedFormValues.publ;
    document.getElementById("playersValue").innerText = document.getElementById("players").value;
    document.getElementById("timeValue").innerText = document.getElementById("time").value;
}

function saveFormValues() {
    const genre = document.getElementById("genre").value;
    const age = document.getElementById("age").value;
    const players = document.getElementById("players").value;
    const time = document.getElementById("time").value;
    const publ = document.getElementById("publ").value;

    const formValues = {
        genre: genre,
        age: age,
        players: players,
        time: time,
        publ: publ
    };

    sessionStorage.setItem("formValues", JSON.stringify(formValues));
}

function getSavedFormValues() {
    const savedFormValues = sessionStorage.getItem("formValues");
    return savedFormValues ? JSON.parse(savedFormValues) : null;
}

function updatePlayerCount() {
    document.getElementById("playersValue").innerText = document.getElementById("players").value;
}

function updatePlayTime() {
    document.getElementById("timeValue").innerText = document.getElementById("time").value;
}