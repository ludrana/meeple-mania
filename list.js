const games = [
    {
        "id": 0,
        "title": "Колонизаторы",
        "image": "https://vsedrugoeshop.ru/upload/ammina.optimizer/jpg-webp/q80/upload/iblock/57b/57b9f24d9552aff08dac13f05f71daf7/M1576_1000x1000.webp",
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
        "image": "https://ir-1.ozone.ru/s3/multimedia-4/wc500/6482663956.jpg",
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
        "image": "https://ir.ozone.ru/s3/multimedia-2/wc500/6613360850.jpg",
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
        "image": "https://ir.ozone.ru/s3/multimedia-b/wc500/6631963031.jpg",
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
        "image": "https://ir.ozone.ru/s3/multimedia-m/wc500/6008635162.jpg",
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

function createCard(data) {
    var newDiv = document.createElement('div');
    newDiv.classList.add('col');
    newDiv.innerHTML = `
    <div class="card shadow-sm">
    <img src=${data.image} width="100%" height="225"> </img>
  
  <div class="card-body">
    <h5>${data.title}</h5>
    <p class="card-text">${data.descShort}</p>
    <div class="d-flex justify-content-between align-items-center">
      <div class="btn-group">
        <a href="pattern.html?game=${data.id}"><button type="button" class="btn btn-sm btn-outline-secondary">Описание</button></a>
        <button type="button" class="btn btn-sm btn-outline-secondary">Добавить в корзину</button>
      </div>
      <small class="text-body-secondary">${data.mintime} - ${data.maxtime}</small>
    </div>
  </div>
</div>
    `;
    return newDiv;
}
  
window.onload = function() {
    const container = document.getElementById('albumContainer');
    games.forEach(game => {
        const card = createCard(game);
        container.appendChild(card);
    });
};
  