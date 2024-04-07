<?php
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {

    $conn = new mysqli("localhost", "root", "root", "meeple_mania");
    $html = "";
    if ($conn->connect_error) {
        error_log("Ошибка: " . $conn->connect_error);
        header("Location: /418.html");
        exit();
    }
    try {
        $sql = "SELECT count(*) AS count FROM purchases WHERE user_id = " . $_SESSION["id"];
        if ($result = $conn->query($sql)) {
            foreach ($result as $row) {
                $amounttotal = $row["count"];
                if ($amounttotal == 0) {
                    $html .= '<div class="col-lg-8 mx-auto"><p class="lead m-4">История покупок пуста</p></div>';
                    return $html;
                } else {
                    $html .= '<div class="row"><div class="col-md-8 order-md-last mx-auto mt-md-4" id="list"><h2 class="d-flex justify-content-center align-items-center mb-5"><span class="text-primary">История покупок</span></h2>';
                }
            }
        }

        $sql = "SELECT DISTINCT purchase_date FROM purchases WHERE user_id = " . $_SESSION["id"];
        if ($result = $conn->query($sql)) {
            foreach ($result as $row) {
                $date = $row["purchase_date"];
                $total = 0;
                $html .= '<h4 class="d-flex mb-3">Заказ от ' . $date . '</h4><ul class="list-group mb-3">';
                $sql1 = "SELECT games.game_id, games.game_name, games.price, purchases.quantity, purchases.is_returned, purchases.purchase_id FROM purchases LEFT JOIN games ON purchases.game_id = games.game_id WHERE purchases.user_id = ? AND purchases.purchase_date = ?";
                $stmt = mysqli_prepare($conn, $sql1);
                mysqli_stmt_bind_param($stmt, "ss", $_SESSION["id"], $date);
                if ($stmt->execute()) {
                    $result1 = $stmt->get_result();
                    foreach ($result1 as $row1) {
                        $gameid = $row1["game_id"];
                        $name = $row1["game_name"];
                        $quantity = $row1["quantity"];
                        $price = $row1["price"];
                        $isreturned = $row1["is_returned"];
                        $id = $row1["purchase_id"];
                        $total += $quantity * $price;
                        $html .= '<li class="list-group-item d-flex justify-content-between lh-sm"><h5 class="my-0"><a href="pattern.html?game=' . $gameid . '"><span class="text-secondary">' . $name . '</span></a></h5><div class="d-flex flex-column flex-md-row align-items-end"><h5 class="text-body-secondary ms-2 me-2">Количество: ' . $quantity . '</h5>' . ($isreturned == 0 ? '<div id="return' . $id . '" class="ms-2 me-2"><h5><a onclick="returnItem(' . $id . ')"><span class="text-danger ml-auto"> Вернуть товар </span></a></h5><div>' : '') . '</div></li>';
                    }
                    $html .= '<li class="list-group-item d-flex justify-content-between"><h5><span>Итого </span></h5><h5><strong><span id="total">' . $total . '</span> ₽</strong></h5></li></ul>';
                }
            }
        }
        $html .= '</div></div>';
        return $html;
    } catch (Exception $e) {
        error_log($e->getMessage());
        header("Location: /418.html");
        exit();
    }
} else {
    header("Location: /main.html");
    exit;
}