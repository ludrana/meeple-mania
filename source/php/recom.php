<?php
$response = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = mysqli_connect("localhost", "root", "root", "meeple_mania");
    if (mysqli_connect_errno()) {
        $response = "Ошибка соединения с базой данных";
        http_response_code(500);
        echo json_encode(array("message" => $response));
        exit();
    }
    $conn->set_charset("utf8");
    try {
        $sql = "SELECT game_id AS id, game_name AS title FROM games WHERE players_min <= ? AND players_max >= ? AND time_min <= ? AND time_max >= ?";
        $params = array($_POST["players"], $_POST["players"], $_POST["time"], $_POST["time"]);
        $types = "ssss";
        if ($_POST["genre"] != 0) {
            $sql .= " AND genre_id = ?";
            $params[] = $_POST["genre"];
            $types .= "s"; 
        }
        if ($_POST["age"] != 0) {
            $sql .= " AND player_age <= ?";
            $params[] = $_POST["age"];
            $types .= "s"; 
        }
        if ($_POST["publ"] != 0) {
            $sql .= " AND publisher_id = ?";
            $params[] = $_POST["publ"];
            $types .= "s";
        }

        $stmt = mysqli_prepare($conn, $sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        $list = array();
        while ($row = $result->fetch_assoc()) {
            $id = $row["id"];
            $title = $row["title"];
            $list[] = array("id" => $id, "title" => $title);
        }
        http_response_code(200);
        echo json_encode($list);
    } catch (Exception $e) {
        $response = "Что-то пошло не так :(";
        http_response_code(500);
        echo json_encode(array("message" => $response));
    } finally {
        $conn->close();
        exit;
    }
} else {
    $response = "Что-то пошло не так :(";
    http_response_code(405);
    echo json_encode(array("message" => $response));
    exit;
}