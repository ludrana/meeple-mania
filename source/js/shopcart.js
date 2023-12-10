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
var total = 0;
// Function that runs when the web page is opened
function onPageLoad() {
  // Check if localStorage is empty
  const container = document.getElementById("cartContainer")
  if (localStorage.length === 0) {
    var emptyDiv = document.createElement('div');
    emptyDiv.classList.add("col-lg-8");
    emptyDiv.classList.add("mx-auto");
    emptyDiv.innerHTML = `<p class="lead m-4">Корзина пуста</p>`;
    container.appendChild(emptyDiv);
  } else {
    
    var html = ``;
    var newDiv = document.createElement('div');
    newDiv.classList.add("row")
    html += `
    <div class="col-md-8 order-md-last mx-auto mt-md-4" id="list">
    <h4 class="d-flex justify-content-between align-items-center mb-3">
      <span class="text-primary">Корзина</span>
      <span class="badge bg-primary rounded-pill" id="itemCount">${localStorage.length}</span>
    </h4>
    <ul class="list-group mb-3">
    `;
    
    
    // Iterate through localStorage items and add an HTML element for each item
    Object.keys(localStorage).forEach(function(key){
      var itemCount = parseInt(localStorage.getItem(key));
      var price = games[parseInt(key)].price;
      var itemName = games[parseInt(key)].title;
      total += price * itemCount;
      html += `
      <li class="list-group-item d-flex justify-content-between lh-sm" id="el${key}">
      <h6 class="my-0">${itemName}</h6>
        <div class="d-flex flex-column flex-md-row align-items-end">
        <a onclick="addToCart(${key})">
          <button class="btn btn-primary btn-sm rounded-circle ms-2 me-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
              <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
            </svg>
          </button>
        </a>
          <h6 class="text-body-secondary" id="count${key}">${itemCount}</h6>
          <a onclick="removeFromCart(${key})">
          <button class="btn btn-primary btn-sm rounded-circle ms-2 me-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-dash-circle" viewBox="0 0 16 16">
              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
              <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8"/>
            </svg>
          </button>
          </a>
          <span class="text-body-secondary ml-auto" id="price${key}">${price * itemCount} ₽</span>
        </div>
      </li>
      `;
   });

   html += `
   <li class="list-group-item d-flex justify-content-between">
      <span>Итого </span>
      <strong id="total">${total} ₽</strong>
      </li>
    </ul>
    <div class="d-flex align-items-center justify-content-center">
      <button class="btn btn-warning btn-lg m-5 p-3 w-75"> Купить </button>
    </div>
    </div>
    
   `;
   newDiv.innerHTML = html;
   container.appendChild(newDiv);
  }
}

// Call the onPageLoad function when the web page is opened
window.onload = onPageLoad;

function addToCart(id) {
    var count = parseInt(localStorage.getItem(id.toString())) + 1;
    localStorage.setItem(id.toString(), count.toString());
    const element = document.getElementById(`count${id}`);
    element.innerHTML = count;
    var price = games[parseInt(id)].price;
    total += price;
    const elTotal = document.getElementById("total");
    const elPrice = document.getElementById(`price${id}`);
    elPrice.innerHTML = `${price * count} ₽`;
    elTotal.innerHTML = `${total} ₽`;
}

function removeFromCart(id) {
  var price = games[parseInt(id)].price;
  if (localStorage.getItem(id.toString()) === "1") {
      localStorage.removeItem(id.toString());
      const element = document.getElementById(`el${id}`);
      element.remove();
      const itemCount = document.getElementById("itemCount");
      itemCount.innerHTML = localStorage.length;
      if (localStorage.length === 0) {
        const container = document.getElementById("cartContainer")
        const list = document.getElementById("list");
        list.remove();
        var emptyDiv = document.createElement('div');
        emptyDiv.classList.add("col-lg-8");
        emptyDiv.classList.add("mx-auto");
        emptyDiv.innerHTML = `<p class="lead m-4">Корзина пуста</p>`;
        container.appendChild(emptyDiv);
      }
  }
  else {
      var count = parseInt(localStorage.getItem(id.toString())) - 1
      localStorage.setItem(id.toString(), count.toString());
      const element = document.getElementById(`count${id}`);
      element.innerHTML = count;
      const elPrice = document.getElementById(`price${id}`);
      elPrice.innerHTML = `${price * count} ₽`;
  }
  total -= price;
  const elTotal = document.getElementById("total");
  elTotal.innerHTML = `${total} ₽`;
}
