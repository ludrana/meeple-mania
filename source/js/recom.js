const games = [
    {
        "id": 0,
        "title": "Колонизаторы",
        "image": "source/img/gameImg/0.webp",
        "genre": "Стратегия",
        "price": 3990,
        "minpeople": 3,
        "maxpeople": 4,
        "mintime": 30,
        "maxtime": 150,
        "age": 10,
        "descShort": "Настольная стратегическая игра, в которой игроки ведут колонизацию новых земель, торгуют и взаимодействуют с другими игроками",
        "descLong": "\"Колонизаторы\" - настольная стратегическая игра, в которой игроки принимают роль колонизаторов, исследующих и колонизирующих новые земли. Игроки торгуют ресурсами, строят поселения и взаимодействуют с другими игроками в поисках победы. Игра привлекает любителей глубоких стратегических игр и предлагает множество тактических возможностей."
    },
    {
        "id": 1,
        "title": "Монополия",
        "image": "source/img/gameImg/1.webp",
        "genre": "Стратегия",
        "price": 1140,
        "minpeople": 2,
        "maxpeople": 8,
        "mintime": 30,
        "maxtime": 180,
        "age": 8,
        "descShort": "Классическая настольная игра, в которой игроки покупают, арендуют и торгуют недвижимостью, стремясь обогатиться и выводить соперников из игры",
        "descLong": "\"Монополия\" - классическая настольная игра, в которой игроки перемещаются по игровой доске, покупают недвижимость, строят дома и отели, а также выманивают деньги у своих соперников. Цель игры - обогатиться, управлять своими финансами и выводить других игроков из игры."
    },
    {
        "id": 2,
        "title": "Шахматы",
        "image": "source/img/gameImg/2.webp",
        "genre": "Стратегия",
        "price": 1140,
        "minpeople": 2,
        "maxpeople": 2,
        "mintime": 30,
        "maxtime": 120,
        "age": 5,
        "descShort": "Стратегическая игра для двух игроков, которая требует планирования и тактического мышления для захвата короля соперника",
        "descLong": "\"Шахматы\" - это классическая настольная игра для двух игроков, которая представляет собой игру на доске, где каждый игрок управляет своими шахматными фигурами в попытке захватить короля соперника. Шахматы требуют высокого уровня стратегического и тактического мышления."
    },
    {
        "id": 3,
        "title": "UNO",
        "image": "source/img/gameImg/3.webp",
        "genre": "Карточная",
        "price": 200,
        "minpeople": 2,
        "maxpeople": 10,
        "mintime": 20,
        "maxtime": 60,
        "age": 7,
        "descShort": "Карточная игра, в которой игроки избавляются от карт, сопоставляя их с предыдущими по цвету или цифре, используя специальные действенные карты для изменения хода игры",
        "descLong": "\"UNO\" - это карточная игра, в которой игроки избавляются от своих карт, сопоставляя их с предыдущими по цвету, цифре или выполняя специальные действия, такие как \"переворот направления\" или \"взять две карты\". Целью игры является избавление от всех карт в руке."
    },
    {
        "id": 4,
        "title": "Каркассон",
        "image": "source/img/gameImg/4.webp",
        "genre": "Стратегия",
        "price": 3523,
        "minpeople": 2,
        "maxpeople": 5,
        "mintime": 30,
        "maxtime": 90,
        "age": 8,
        "descShort": "Настольная игра, в которой игроки поочередно кладут плитки для создания ландшафта, размещают своих фигурок и соревнуются за контроль над городами, дорогами и фермами",
        "descLong": "\"Каркассон\" - настольная игра, в которой игроки поочередно кладут плитки для создания ландшафта, размещают своих фигурок и соревнуются за контроль над городами, дорогами и фермами. Игра предлагает комбинацию стратегического планирования и взаимодействия с другими игроками."
    }
]
;

window.onload = function () {
    var genreSelect = document.getElementById("genre");
    const uniqueGenres = [...new Set(games.map(game => game.genre))];
    uniqueGenres.forEach(function (genre) {
        var option = document.createElement("option");
        option.value = genre;
        option.textContent = genre;
        genreSelect.appendChild(option);
    });
};

function getRecommendedGames() {
    const selectedGenre = document.getElementById("genre").value;
    const selectedAge = parseInt(document.getElementById("age").value);
    const selectedPlayers = parseInt(document.getElementById("players").value);
    const selectedTime = parseInt(document.getElementById("time").value);

    const recommendedGames = games.filter(game => 
        game.genre === selectedGenre &&
        game.age <= selectedAge &&
        game.minpeople <= selectedPlayers &&
        game.maxpeople >= selectedPlayers &&
        game.mintime <= selectedTime &&
        game.maxtime >= selectedTime
    );

    const resultElement = document.getElementById("result");
    if (recommendedGames.length > 0) {
        resultElement.innerHTML = "<p>Рекомендуемые игры:</p>";
        recommendedGames.forEach(game => {
            resultElement.innerHTML += `<a href="pattern.html?game=${game.id}"><button class="game-button">${game.title}</button></a>`;
        });
    } else {
        resultElement.textContent = "Игры не найдены. Попробуйте изменить параметры.";
    }
}
