<?php
session_start();
$_SESSION["err"] = false;
$_SESSION["errdiv"] = "";
$_SESSION["succdiv"] = "";
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: /main.html");
    exit;
}

$link = new mysqli("localhost", "root", "root", "meeple_mania");
if ($link->connect_error) {
    error_log($link->connect_error);
    $_SESSION["err"] = true;
    $_SESSION["errdiv"] = '<div class="card" style="background-color: #f8d7da;border-color: #f5c6cb;color: #721c24;"><div class="card-body">Ошибка соединения с базой данных</div></div><p></p>';
    header("location: /login.html");
    exit();
}
$email = $password = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION["errdiv"] = '<div class="card" style="background-color: #f8d7da;border-color: #f5c6cb;color: #721c24;"><div class="card-body">';
    if (empty($_POST["email"])) {
        $_SESSION["err"] = true;
        $_SESSION["errdiv"] .= '<p>Введите адрес электронной почты</p>';
    } else {
        $email = trim($_POST["email"]);
    }
    if (empty($_POST["password"])) {
        $_SESSION["err"] = true;
        $_SESSION["errdiv"] .= '<p>Введите пароль</p>';
    } else {
        $password = $_POST["password"];
    }

    if ($_SESSION["err"] == true) {
        $_SESSION["errdiv"] .= '</div></div><p></p>';
        header("location: /login.html");
        $link->close();
        exit();
    }

    if (!empty($email) && !empty($password)) {
        $sql = "SELECT user_id, user_email, user_password FROM users WHERE user_email = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = $email;
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            header("location: /main.html");
                            exit;
                        } else {
                            $_SESSION["err"] = true;
                            $_SESSION["errdiv"] .= '<p>Неверное имя пользователя или пароль</p>';
                        }
                    }
                } else {
                    $_SESSION["err"] = true;
                    $_SESSION["errdiv"] .= '<p>Неверное имя пользователя или пароль</p>';
                }
            } else {
                $_SESSION["err"] = true;
                $_SESSION["errdiv"] .= '<p>Что-то пошло не так :(</p>';
            }
            mysqli_stmt_close($stmt);
        }
    }
    $_SESSION["errdiv"] .= '</div></div><p></p>';
    header("location: /login.html");
    $link->close();
    exit();
} else {
    header("location: /main.html");
    exit();
}