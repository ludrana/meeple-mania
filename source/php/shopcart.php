<?php
if (isset ($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
    
    $conn = new mysqli("localhost", "root", "", "meeple_mania");
    $html = "";
    if ($conn->connect_error) {
        error_log("". $conn->connect_error);
        header("Location: /418.html");
        exit();
    }
    $sql = "SELECT SUM(shopcart.quantity) AS sum FROM shopcart WHERE shopcart.user_id = " . $_SESSION["id"];
    if ($result = $conn->query($sql)) {
        foreach ($result as $row) {
            $amounttotal = $row["sum"];
            if (empty($amounttotal)) {
                $html.= '<div class="col-lg-8 mx-auto"><p class="lead m-4">Корзина пуста</p></div>';
                return $html;
            } else {
                $html.= '<div class="row"><div class="col-md-8 order-md-last mx-auto mt-md-4" id="list"><h3 class="d-flex justify-content-between align-items-center mb-3"><span class="text-primary">Корзина</span><span class="badge bg-primary rounded-pill" id="itemCount">'.$amounttotal.'</span></h3><ul class="list-group mb-3" id="ul_items">';
            }
        }
    } else {
        error_log(''. $conn->error);
        $conn->close();
        header("Location: /418.html");
        exit();
    }
    $sql = "SELECT shopcart.game_id, games.game_name, shopcart.quantity, games.price FROM shopcart LEFT JOIN games ON shopcart.game_id = games.game_id  WHERE shopcart.user_id = " . $_SESSION["id"];
    $total = 0;
    if ($result = $conn->query($sql)) {
        foreach ($result as $row) {
            $name = $row["game_name"];
            $quantity = $row["quantity"];
            $gameid = $row["game_id"];
            $price = $row["price"];
            $total += $price * $quantity;

            $html .= '<li class="list-group-item d-flex justify-content-between lh-sm" id="el'.$gameid.'"><h5 class="my-0"><a href="pattern.html?game='.$gameid.'"><span class="text-secondary">'.$name.'</span></a></h5><div class="d-flex flex-column flex-md-row align-items-end"><a onclick="addToCart('.$gameid.')"><button class="btn btn-primary btn-sm rounded-circle ms-2 me-2"><span class="material-symbols-outlined">add</span></button></a><h5 class="text-body-secondary" id="count'.$gameid.'">'.$quantity.'</h5><a onclick="removeFromCart('.$gameid.')"><button class="btn btn-primary btn-sm rounded-circle ms-2 me-2"><span class="material-symbols-outlined">remove</span></button></a><a onclick="removeAll('.$gameid.')"><button class="btn btn-primary btn-sm rounded-circle ms-2 me-2"><span class="material-symbols-outlined">delete</span></button></a><h5><span class="text-body-secondary ml-auto" id="price'.$gameid.'">' . $quantity * $price . '</span> ₽</h5></div></li>';
        }
    } else {
        error_log(''. $conn->error);
        $conn->close();
        header("Location: /418.html");
        exit();
    }
    $html.= '<li class="list-group-item d-flex justify-content-between"><h5><span>Итого </span></h5><h5><strong><span id="total">'.$total.'</span> ₽</strong></h5></li></ul><div class="d-flex align-items-center justify-content-center"><button class="btn btn-warning btn-lg m-5 p-3 w-75" onclick="buy()"> Купить </button></div></div></div>';
    $conn->close();
    return $html;
} else {
    header("Location: /main.html");
    exit;
}