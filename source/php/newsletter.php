<?php
$response = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $conn = mysqli_connect("localhost","root","","meeple_mania");
        if (mysqli_connect_errno()) {
            error_log(mysqli_connect_error());
            $response = "Ошибка соединения с базой данных";
            http_response_code(500);
            echo json_encode(array("message" => $response));
            exit();
        }
        try {
            $sql = "INSERT newsletters (email) VALUES (?)";
            $stmt = mysqli_prepare($conn, $sql);
            $stmt->bind_param("s", $_POST["email"]);
            $stmt->execute();
            $response = "Вы подписались на рассылку.";
            http_response_code(201);
            echo json_encode(array("message" => $response));
        }
        catch (Exception $e) {
            error_log($e ->getMessage());
            $response = "Вы уже подписаны на рассылку";
            http_response_code(400);
            echo json_encode(array("message" => $response));
        }
        finally {
            $stmt->close();
            $conn->close();
            exit;
        }
    } else {
        $response = "Некорректный email";
        http_response_code(400);
        echo json_encode(array("message" => $response));
        exit;
    }
} else {
    $response = "Что-то пошло не так :(";
    http_response_code(405);
    echo json_encode(array("message" => $response));
    exit;
}