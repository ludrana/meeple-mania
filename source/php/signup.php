<?php
session_start();
$_SESSION["err"] = false;
$_SESSION["errdiv"] = "";
$_SESSION["succdiv"] = "";
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: /main.html");
    exit;
}
$username = $email = $password = $confirm_password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $link = new mysqli("localhost", "root", "", "meeple_mania");
    if ($link->connect_error) {
        error_log($link->connect_error);
        $_SESSION["err"] = true;
        $_SESSION["errdiv"] = '<div class="card" style="background-color: #f8d7da;border-color: #f5c6cb;color: #721c24;"><div class="card-body">Ошибка соединения с базой данных</div></div><p></p>';
        header("location: /signup.html");
        exit();
    }

    $_SESSION["errdiv"] = '<div class="card" style="background-color: #f8d7da;border-color: #f5c6cb;color: #721c24;"><div class="card-body">';

    if (empty($_POST["email"])) {
        $_SESSION["err"] = true;
        $_SESSION["errdiv"] .= '<p>Введите адрес электронной почты</p>';
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $_SESSION["err"] = true;
        $_SESSION["errdiv"] .= '<p>Введите валидный адрес электронной почты</p>';
    } else {
        $sql = "SELECT user_id FROM users WHERE user_email = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = $_POST["email"];
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $_SESSION["err"] = true;
                    $_SESSION["errdiv"] .= '<p>У вас уже есть аккаунт</p>';
                } else {
                    $email = $_POST["email"];
                }
            } else {
                $_SESSION["err"] = true;
                $_SESSION["errdiv"] .= '<p>Что-то пошло не так :(</p>';
            }
            mysqli_stmt_close($stmt);
        }
    }
    if (empty($_POST["password"])) {
        $_SESSION["err"] = true;
        $_SESSION["errdiv"] .= '<p>Введите пароль</p>';
    } elseif (strlen($_POST["password"]) < 6) {
        $_SESSION["err"] = true;
        $_SESSION["errdiv"] .= '<p>Минимальная длина пароля - 6 символов</p>';
    } else {
        $password = $_POST["password"];
    }
    if (empty($_POST["repeatpassword"])) {
        $_SESSION["err"] = true;
        $_SESSION["errdiv"] .= '<p>Повторите пароль</p>';
    } else {
        $confirm_password = $_POST["repeatpassword"];
        if ($_SESSION["err"] == false && ($password != $confirm_password)) {
            $_SESSION["err"] = true;
            $_SESSION["errdiv"] .= '<p>Пароли не совпадают</p>';
        }
    }
    if (empty($_POST["username"])) {
        $_SESSION["err"] = true;
        $_SESSION["errdiv"] .= '<p>Введите имя пользователя</p>';
    } elseif (!preg_match('/^[\p{L}a-zA-Z0-9 ]+$/u', trim($_POST["username"]))) {
        $_SESSION["err"] = true;
        $_SESSION["errdiv"] .= '<p>Имя пользователя должно содержать только буквы, цифры и пробелы</p>';
    } else {
        $username = $_POST["username"];
    }
    if ($_SESSION["err"] == false) {
        $sql = "INSERT INTO users (user_name, user_email, user_password) VALUES (?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_email = $email;
            if (mysqli_stmt_execute($stmt)) {
                $stmt1 = $link->prepare("SELECT user_id FROM users WHERE user_email = ?");
                $stmt1->bind_param("s", $email);
                $stmt1->execute();
                $userid = $stmt1->get_result()->fetch_object()->user_id;
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $userid;
                header("location: /main.html");
                mysqli_stmt_close($stmt);
                mysqli_close($link);
                exit;
            } else {
                $_SESSION["err"] = true;
                $_SESSION["errdiv"] .= '<p>Что-то пошло не так :(</p>';
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($link);
    $_SESSION["errdiv"] .= '</div></div><p></p>';
    header("location: /signup.html");
    exit;
} else {
    header("location: /main.html");
    exit();
}