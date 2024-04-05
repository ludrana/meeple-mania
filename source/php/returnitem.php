<?php
$response = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = mysqli_connect("localhost", "root", "", "meeple_mania");
    if (mysqli_connect_errno()) {
        error_log(mysqli_connect_error());
        $response = "Ошибка соединения с базой данных";
        http_response_code(500);
        echo json_encode(array("message" => $response));
        exit();
    }
    try {
        $sql = "UPDATE purchases SET is_returned = 1 WHERE purchase_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        $stmt->bind_param("s", $_POST["id"]);
        $stmt->execute();
        $response = "Возврат товара осуществлен.";
        http_response_code(200);
        echo json_encode(array("message" => $response));
    } catch (Exception $e) {
        error_log($e->getMessage());
        $response = "Что-то пошло не так :(";
        http_response_code(500);
        echo json_encode(array("message" => $response));
    } finally {
        $stmt->close();
        $conn->close();
        exit;
    }
} else {
    $response = "Что-то пошло не так :(";
    http_response_code(405);
    echo json_encode(array("message" => $response));
    exit;
}