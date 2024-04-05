<?php
$response = "";
$price = 0;
$quantity = 0;
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset ($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
    $link = mysqli_connect("localhost", "root", "", "meeple_mania");
    if (mysqli_connect_errno()) {
        error_log(mysqli_connect_error());
        $response = "Ошибка соединения с базой данных";
        http_response_code(500);
        echo json_encode(array("message" => $response));
        exit();
    }
    $sql = "SELECT shopcart.quantity, games.price FROM shopcart LEFT JOIN games ON shopcart.game_id = games.game_id WHERE user_id = ? AND shopcart.game_id = ?;";
    try {
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $_SESSION["id"], $_POST["gameid"]);
        try {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt, $quantity, $price);
            mysqli_stmt_fetch($stmt);
                try {
                    $sql1 = "DELETE FROM shopcart WHERE user_id = ? AND game_id = ?;";
                    $stmt1 = mysqli_prepare($link, $sql1);
                    mysqli_stmt_bind_param($stmt1, "ss", $_SESSION["id"], $_POST["gameid"]);
                    mysqli_stmt_execute($stmt1);
                    if (mysqli_stmt_error($stmt1)) {
                        error_log(mysqli_stmt_error($stmt1));
                        $response = "Что-то пошло не так :(";
                        http_response_code(500);
                        echo json_encode(array("message" => $response));
                    } else {
                        $response = "Товар(ы) удален(ы) из корзины";
                        http_response_code(200);
                        echo json_encode(array("message" => $response, "price"=> $price,"quantity"=> $quantity));
                    }
                } catch (Exception $e) {
                    error_log($e->getMessage());
                    $response = "Что-то пошло не так :(";
                    http_response_code(500);
                    echo json_encode(array("message" => $response));
                } finally {
                    mysqli_stmt_close($stmt1);
                }
        } catch (Exception $e) {
            error_log($e->getMessage());
            $response = "Что-то пошло не так :(";
            http_response_code(500);
            echo json_encode(array("message" => $response));
        } finally {
            mysqli_stmt_close($stmt);
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        $response = "Что-то пошло не так :(";
        http_response_code(500);
        echo json_encode(array("message" => $response));
    } finally {
        mysqli_close($link);
    }
} else {
    $response = "Авторизуйтесь, чтобы удалять товары из корзины";
    http_response_code(401);
    echo json_encode(array("message" => $response));
}