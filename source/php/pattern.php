<?php
$conn = new mysqli("localhost", "root", "root", "meeple_mania");
$html = "";
$name = "";
$gameid = $_GET["game"] ?? 1;
if ($conn->connect_error) {
    error_log($conn->connect_error);
    header("Location: /418.html");
    exit();
}
try {
    $sql = "SELECT games.game_name, games.players_min, games.players_max, games.time_min, games.time_max, games.player_age, games.price, games.desc_long, games.total_purchased, COALESCE(AVG(ratings.rating_value), 0) AS rating, (SELECT publishers.publisher_name FROM publishers WHERE publishers.publisher_id = games.publisher_id) AS publisher, (SELECT genres.genre_name FROM genres WHERE genres.genre_id = games.genre_id) AS genre FROM games LEFT JOIN ratings ON games.game_id = ratings.game_id  WHERE games.game_id = " . $gameid . " GROUP BY games.game_id;";
    if ($result = $conn->query($sql)) {
        foreach ($result as $row) {
            $name = $row["game_name"];
            $pmin = $row["players_min"];
            $pmax = $row["players_max"];
            $tmin = $row["time_min"];
            $tmax = $row["time_max"];
            $age = $row["player_age"];
            $price = $row["price"];
            $desc = $row["desc_long"];
            $rating = round($row["rating"], 2);
            $total = $row["total_purchased"];
            $publ = $row["publisher"];
            $genre = $row["genre"];


            $ratinghtml = "";
            if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
                $ratingval = $conn->query("SELECT rating_value FROM ratings WHERE user_id = " . $_SESSION["id"] . " AND game_id = " . $gameid)->fetch_object()->rating_value ?? 0;
                $ratinghtml = '<div class="p-3"><form id="ratingForm"><fieldset class="rating"><legend>Оцените игру:</legend><input type="radio" id="star5" name="rating" value="5" ' . ($ratingval == 5 ? "checked" : "") . '/><label for="star5" >5 stars</label><input type="radio" id="star4" name="rating" value="4" ' . ($ratingval == 4 ? "checked" : "") . '/><label for="star4" >4 stars</label><input type="radio" id="star3" name="rating" value="3" ' . ($ratingval == 3 ? "checked" : "") . '/><label for="star3" >3 stars</label><input type="radio" id="star2" name="rating" value="2" ' . ($ratingval == 2 ? "checked" : "") . '/><label for="star2" >2 stars</label><input type="radio" id="star1" name="rating" value="1" ' . ($ratingval == 1 ? "checked" : "") . '/><label for="star1" >1 star</label></fieldset><div class="clearfix"></div><button class="submit clearfix btn btn-warning">Отправить</button></form></div>';
            } else {
                $ratinghtml = '<div class="p-3"><p> Авторизуйтесь, чтобы оценивать игры. </div>';
            }

            $html .= '<div class="container mt-5 col-md-8"><div class="justify-content-center align-items-center d-flex"><div class="p-3"><h2 id="gameTitle">' . $name . '</h2></div><div class="p-3"><span class="material-symbols-outlined">star</span> ' . $rating . '</div></div><div class="row justify-content-between align-items-center d-flex"><div class="col-md-4"><img id="gameCover" src="source/img/game-cover/' . $gameid . '.jpg" alt="Обложка игры" height="300px"></div><div class="card justify-content-center align-items-center p-2 col-md-4"><p><strong>Жанр:</strong> <span id="gameGenre">' . $genre . '</span></p><p><strong>Издатель:</strong> <span id="gameGenre">' . $publ . '</span></p><p><span class="material-symbols-outlined">person</span> <span id="gamePlayers"> ' . $pmin . ' - ' . $pmax . ' </span></p><p><span class="material-symbols-outlined">timer</span> <span id="gameTime"> ' . $tmin . ' - ' . $tmax . ' минут</span></p><p><strong>Возраст игроков:</strong> <span id="gameAge"> ' . $age . '+</span></p><p><strong>Стоимость:</strong> <span id="gamePrice"> ' . $price . ' ₽</span></p><p><strong>Всего продано:</strong> <span id="gamePrice"> ' . $total . '</span></p><a onclick="addToCart(' . $gameid . ')"><button type="button" class="btn btn btn-secondary">Добавить в корзину</button></a></div></div><div class="mt-4">' . $ratinghtml . '<h3>Описание</h3><p id="gameDescription">' . $desc . '</p></div></div>';
        }
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: /418.html");
    exit();
} finally {
    $conn->close();
}