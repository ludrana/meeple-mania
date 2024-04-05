<?php
session_start();
if (isset ($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $link = new mysqli("localhost", "root", "", "meeple_mania");
    if ($link->connect_error) {
        error_log($link->connect_error);
        header("Location: /418.html");
        exit();
    }
    $userdata = array();
    if ($stmt = $link->prepare("SELECT user_name, user_email, game_id, pfp FROM users WHERE user_id = ?")) {
        mysqli_stmt_bind_param($stmt, "s", $param_id);
        $param_id = $_SESSION["id"];
        
        $userdata['total'] = $link->query("SELECT full_sum(". $param_id .") AS total;")->fetch_object()->total;

        if($stmt->execute()){
            $result = $stmt->get_result();
            foreach($result as $row){
                $userdata['username'] = $row["user_name"];
                $userdata['email'] = $row["user_email"];
                $userdata['gameid'] = $row["game_id"];
                $userdata['pfp'] = $row["pfp"];
                if (empty ($row["game_id"])) {
                    $userdata['gamename'] = "Не выбрано";
                } else {
                    $userdata['gamename'] = $link->query("SELECT game_name FROM games WHERE game_id = " . $row["game_id"])->fetch_object()->game_name;
                }
            }
        } else {
            header("Location: /418.html");
            exit();
        }
    } else {
        header("Location: /418.html");
        exit();
    }
    mysqli_stmt_close($stmt);
    return $userdata;
} else {
    header("location: /main.html");
    exit();
}