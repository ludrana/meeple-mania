<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "meeple_mania");
if ($conn->connect_error) {
    error_log($link->connect_error);
    header("Location: /418.html");
    exit();
}
$headerdata = array();
$headerdata['style_out'] = "";
$headerdata['style_in'] = "";
$headerdata['pfp'] = "";
try {
    $gamecount = $conn->query("SELECT COUNT(*) AS num FROM games;")->fetch_object()->num;
    $gamelink = 'pattern.html?game=' . rand(1, $gamecount);
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        $headerdata['style_out'] = "style='display:none;'";
        $headerdata['style_in'] = "style='display:block;'";
        $headerdata['pfp'] = $conn->query('SELECT pfp FROM users WHERE user_id = ' . $_SESSION["id"])->fetch_object()->pfp;
    } else {
        $headerdata['style_in'] = "style='display:none;'";
        $headerdata['style_out'] = "style='display:block;'";
        $headerdata['pfp'] = "source/img/user-pfp/default.jpg";
    }
} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    header("Location: /418.html");
    exit();
} finally {
    $conn->close();
}