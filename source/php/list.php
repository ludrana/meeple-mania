<?php

$conn = new mysqli("localhost", "root", "", "meeple_mania");
$html = "";
if($conn->connect_error){
    error_log("".$conn->connect_error);
    header("Location: /418.html");
    exit();
}
$sql = "SELECT games.game_id, games.game_name, games.player_age, games.price, games.desc_short, COALESCE(AVG(ratings.rating_value), 0) AS rating FROM games LEFT JOIN ratings ON games.game_id = ratings.game_id GROUP BY games.game_id;";
if($result = $conn->query($sql)){
    foreach($result as $row){
        $gameid = $row["game_id"];
        $name = $row["game_name"];
        $age = $row["player_age"];
        $price = $row["price"];
        $desc = $row["desc_short"];
        $rating = round($row["rating"], 2);
        $html.='<div class="col"><div class="card shadow-sm"><img src="source/img/game-cover/'.$gameid.'.jpg" width="100%" height="225"> </img><div class="card-body"><h5>'.$name.'</h5><p><span class="material-symbols-outlined">star</span><small class="text-body-secondary"> '.$rating.'</small></p><p class="card-text">'.$desc.'</p><div class="d-flex justify-content-between align-items-center p-1"><small class="text-body-secondary">'.$age.'+</small><small class="text-body-secondary">'.$price.' ₽</small></div><div class="d-flex justify-content-between align-items-center p-1"><div class="btn-group"><a href="pattern.html?game='.$gameid.'"><button type="button" class="btn btn-sm btn-outline-secondary">Описание</button></a></div><a onclick="addToCart('.$gameid.')" id="g'.$gameid.'"><button type="button" class="btn btn-sm btn-outline-secondary">Добавить в корзину</button></a></div></div></div></div>';
    }
    $conn->close();
    return $html;
} else {
    error_log(''.$conn->error);
    $conn->close();
    header("Location: /418.html");
    exit();
}