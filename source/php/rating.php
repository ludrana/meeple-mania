<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
    $link = mysqli_connect("localhost", "root", "", "meeple_mania");
    if (mysqli_connect_errno()) {
        error_log(mysqli_connect_error());
        $response = "Ошибка соединения с базой данных";
        http_response_code(500);
        echo json_encode(array("message" => $response));
        exit();
    }
    $sql = "SELECT COUNT(*) FROM ratings WHERE user_id = ? AND game_id = ?;";
    try {
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $_SESSION["id"], $_POST["game"]);
        try {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt, $count);
            mysqli_stmt_fetch($stmt);
            if ($count == 1) {
                try {
                    $sql1 = "UPDATE ratings SET rating_value = ? WHERE user_id = ? AND game_id = ?;";
                    $stmt1 = mysqli_prepare($link, $sql1);
                    mysqli_stmt_bind_param($stmt1, "sss", $_POST["ratingval"], $_SESSION["id"], $_POST["game"]);
                    mysqli_stmt_execute($stmt1);
                    if (mysqli_stmt_error($stmt1)) {
                        error_log(mysqli_stmt_error($stmt1));
                        $response = "Что-то пошло не так :(";
                        http_response_code(500);
                        echo json_encode(array("message" => $response));
                    } else {
                        $response = "Оценка обновлена";
                        http_response_code(200);
                        echo json_encode(array("message" => $response));
                    }
                } catch (Exception $e) {
                    error_log($e->getMessage());
                    $response = "Что-то пошло не так :(";
                    http_response_code(500);
                    echo json_encode(array("message" => $response));
                } finally {
                    mysqli_stmt_close($stmt1);
                }
            } else {
                try {
                    $sql1 = "INSERT ratings (user_id, game_id, rating_value) VALUES ( ?, ?, ? )";
                    $stmt1 = mysqli_prepare($link, $sql1);
                    mysqli_stmt_bind_param($stmt1, "sss", $_SESSION["id"], $_POST["game"], $_POST["ratingval"]);
                    mysqli_stmt_execute($stmt1);
                    if (mysqli_stmt_error($stmt1)) {
                        error_log(mysqli_stmt_error($stmt1));
                        $response = "Что-то пошло не так :(";
                        http_response_code(500);
                        echo json_encode(array("message" => $response));
                    } else {
                        $response = "Оценка обновлена";
                        http_response_code(200);
                        echo json_encode(array("message" => $response));
                    }
                } catch (Exception $e) {
                    error_log($e->getMessage());
                    $response = "Что-то пошло не так :(";
                    http_response_code(500);
                    echo json_encode(array("message" => $response));
                } finally {
                    mysqli_stmt_close($stmt1);
                }
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
    $response = "Авторизуйтесь, чтобы оценивать игры";
    http_response_code(401);
    echo json_encode(array("message" => $response));
}