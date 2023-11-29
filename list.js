const games = [
    {
        "title": "Колонизаторы",
        "genre": "Стратегия",
        "publisher": "Издатель 1",
        "minpeople": 2,
        "maxpeople": 8,
        "mintime": 30,
        "maxtime": 150,
        "age": "Дети"
    },
    {
        "title": "Монополия",
        "genre": "Настольная",
        "publisher": "Издатель 2",
        "minpeople": 2,
        "maxpeople": 8,
        "mintime": 30,
        "maxtime": 150,
        "age": "Дети"
    },
    {
        "title": "Шахматы",
        "genre": "Стратегия",
        "publisher": "Издатель 3",
        "minpeople": 2,
        "maxpeople": 8,
        "mintime": 30,
        "maxtime": 150,
        "age": "Дети"
    },
    {
        "title": "UNO",
        "genre": "Карточная",
        "publisher": "Издатель 4",
        "minpeople": 2,
        "maxpeople": 8,
        "mintime": 30,
        "maxtime": 150,
        "age": "Дети"
    },
    {
        "title": "Каркассон",
        "genre": "Стратегия",
        "publisher": "Издатель 5",
        "minpeople": 2,
        "maxpeople": 8,
        "mintime": 30,
        "maxtime": 150,
        "age": "Дети"
    }
];

function createCard(data) {
    var newDiv = document.createElement('div');
    newDiv.classList.add('col');
    newDiv.innerHTML = `
      <div class="card shadow-sm">
        <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
          <title>${data.title}</title>
          <rect width="100%" height="100%" fill="#55595c"/>
          <text x="50%" y="50%" fill="#eceeef" dy=".3em">${data.title}</text>
        </svg>
        <div class="card-body">
          <p class="card-text">${data.genre}</p>
          <div class="d-flex justify-content-between align-items-center">
            <div class="btn-group">
              <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
              <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
            </div>
            <small class="text-body-secondary">${data.mintime}</small>
          </div>
        </div>
      </div>
    `;
    return newDiv;
}
  
window.onload = function() {
    const container = document.getElementById('albumContainer'); // Replace with the ID of the container element
    games.forEach(game => {
        const card = createCard(game);
        container.appendChild(card);
    });
};
  