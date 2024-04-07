<?php
if (isset ($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $conn = new mysqli("localhost", "root", "root", "meeple_mania");
    $favgame = $conn->query("SELECT game_id FROM users WHERE user_id = " . $_SESSION["id"])->fetch_object()->game_id;

    $html = "";
    if ($conn->connect_error) {
        error_log($conn->connect_error);
        header("Location: /418.html");
        exit();
    }
    $sql = "SELECT game_id, game_name FROM games";
    if ($result = $conn->query($sql)) {
        foreach ($result as $row) {
            $name = $row["game_name"];
            $id = $row["game_id"];
            $selected = $favgame == $id ? 'selected' : '';
            $html .= '<option value="' . $id . '" ' . $selected . '>' . $name . '</option>';
        }
        $conn->close();
        return $html;
    } else {
        error_log($conn->error);
        $conn->close();
        header("Location: /418.html");
        exit();
    }
} else {
    header("Location: /main.html");
    exit();
}