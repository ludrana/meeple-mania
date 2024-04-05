<?php
session_start();
$response = "";
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $mysqli = new mysqli('localhost', 'root', '', 'meeple_mania');

    if ($mysqli->connect_error) {
        error_log(mysqli_connect_error());
        $response = "Ошибка соединения с базой данных";
        http_response_code(500);
        echo json_encode(array("message" => $response));
        exit();
    }

    try {
        foreach ($mysqli->query("SELECT COUNT(*) AS count FROM shopcart WHERE shopcart.user_id = " . $_SESSION["id"]) as $row) {
            if ($row['count'] == 0) {
                $response = "Корзина пуста";
                http_response_code(400);
                echo json_encode(array("message" => $response));
                exit();
            }
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        $response = "Что-то пошло не так :(";
        http_response_code(500);
        echo json_encode(array("message" => $response));
        $mysqli->close();
        exit();
    }

    try {
        $mysqli->begin_transaction();

        $sql = "SELECT game_id, quantity FROM shopcart WHERE user_id = " . $_SESSION["id"];
        if ($result = $mysqli->query($sql)) {
            foreach ($result as $row) {
                $gameid = $row["game_id"];
                $quantity = $row["quantity"];

                $mysqli->query("INSERT purchases (user_id, game_id, quantity) VALUES (".$_SESSION["id"].", ".$gameid.", ".$quantity.")");
            }
        }
        $mysqli->query('DELETE FROM shopcart WHERE user_id = ' . $_SESSION['id']);
        $mysqli->commit();
        $response = "Покупка прошла успешно";
        http_response_code(200);
        echo json_encode(array("message" => $response));
    } catch (Exception $e) {
        $mysqli->rollback();
        error_log($e->getMessage());
        $response = "Что-то пошло не так :(";
        http_response_code(500);
        echo json_encode(array("message" => $response));
    } finally {
        $mysqli->close();
        exit();
    }
} else {
    header("Location: /main.html");
    exit;
}