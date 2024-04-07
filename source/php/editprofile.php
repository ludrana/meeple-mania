<?php
session_start();
$_SESSION["err"] = false;
$_SESSION["errdiv"] = "";
$_SESSION["succdiv"] = "";
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SERVER["REQUEST_METHOD"] == "POST") {
    $link = new mysqli("localhost", "root", "root", "meeple_mania");
    if ($link->connect_error) {
        error_log($link->connect_error);
        $_SESSION["err"] = true;
        $_SESSION["errdiv"] = '<div class="card" style="background-color: #f8d7da;border-color: #f5c6cb;color: #721c24;"><div class="card-body">Ошибка соединения с базой данных</div></div><p></p>';
        header("location: /lkabedit.html");
        exit();
    }
    $olduserdata = array();
    if ($stmt = $link->prepare("SELECT user_name, game_id, pfp, user_password FROM users WHERE user_id = ?;")) {
        $param_id = $_SESSION["id"];
        mysqli_stmt_bind_param($stmt, "s", $param_id);
        if ($stmt->execute()) {
            $_SESSION["errdiv"] = '<div class="card" style="background-color: #f8d7da;border-color: #f5c6cb;color: #721c24;"><div class="card-body">';
            $result = $stmt->get_result();
            foreach ($result as $row) {
                $olduserdata['username'] = $row["user_name"];
                $olduserdata['gameid'] = $row["game_id"];
                $olduserdata['pfp'] = $row["pfp"];
                $olduserdata['psw'] = $row["user_password"];
            }
            $newUsername = $newGameid = $newDob = "";
            if (!empty($_POST["newUsername"])) {
                if (preg_match('/^[\p{L}a-zA-Z0-9 ]+$/u', $_POST["newUsername"])) {
                    $newUsername = $_POST["newUsername"];
                } else {
                    $_SESSION["err"] = true;
                    $_SESSION["errdiv"] .= '<p>Имя пользователя должно содержать только буквы, цифры и пробелы</p>';
                }
            } else {
                $newUsername = $olduserdata['username'];
            }
            $newGameid = $_POST["newGameid"];

            $target_file = $olduserdata['pfp'];
            if (!(!file_exists($_FILES["newPfp"]['tmp_name']) || !is_uploaded_file($_FILES["newPfp"]['tmp_name']))) {
                $target_dir = "../img/user-pfp/u" . $_SESSION["id"];
                $uploadOk = 1;
                $target_file = $target_dir . basename($_FILES["newPfp"]["name"]);
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                if (isset($_POST["newPfp"])) {
                    $check = getimagesize($_FILES["newPfp"]["tmp_name"]);
                    if ($check !== false) {
                        $uploadOk = 1;
                    } else {
                        $_SESSION["err"] = true;
                        $_SESSION["errdiv"] .= '<p>Файл не является изображением</p>';
                        $uploadOk = 0;
                    }
                }
                $uploadRes = 0;
                if ($uploadOk == 0) {
                } else {
                    if (move_uploaded_file($_FILES["newPfp"]["tmp_name"], $target_file)) {
                        $uploadOk = 1;
                        $target_file = str_replace("..", "source", $target_file);
                    } else {
                        $_SESSION["err"] = true;
                        $_SESSION["errdiv"] .= '<p>Не удалось загрузить изображение</p>';
                    }
                }
            }

            $newpswhash = $olduserdata['psw'];
            if (!empty($_POST["oldpsw"]) || !empty($_POST["newpsw"])) {
                if (empty($_POST["oldpsw"])) {
                    $_SESSION["err"] = true;
                    $_SESSION["errdiv"] .= '<p>Введите старый пароль для смены пароля</p>';
                } else {
                    if (password_verify($_POST["oldpsw"], $olduserdata['psw'])) {
                        if (strlen($_POST["newpsw"]) > 5) {
                            if ($_POST["newpsw"] == $_POST["repeatpsw"]) {
                                $newpswhash = password_hash($_POST["newpsw"], PASSWORD_DEFAULT);
                            } else {
                                $_SESSION["err"] = true;
                                $_SESSION["errdiv"] .= '<p>Пароли не совпадают</p>';
                            }
                        } else {
                            $_SESSION["err"] = true;
                            $_SESSION["errdiv"] .= '<p>Минимальная длина пароля - 6 символов</p>';
                        }
                    } else {
                        $_SESSION["err"] = true;
                        $_SESSION["errdiv"] .= '<p>Неверный пароль</p>';
                    }
                }
            }

            if ($_SESSION["err"] == true) {
                $_SESSION["errdiv"] .= '</div></div><p></p>';
                header("location: /lkabedit.html");
                $link->close();
                exit();
            }

            $sql = "UPDATE users SET user_name = ?, user_password = ?, game_id = ?, pfp = ? WHERE user_id = " . $_SESSION["id"];

            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "ssss", $param_username, $param_psw, $param_gameid, $param_pfp);

                $param_username = $newUsername;
                $param_psw = $newpswhash;
                $param_gameid = $newGameid;
                $param_pfp = $target_file;

                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION["succdiv"] = '<div class="card" style="background-color: #d7f8de;border-color: #c6f5c9;color: #1c721d;"><div class="card-body">Данные сохранены</div></div><p></p>';
                    header("location: /lkab.html");
                } else {
                    error_log(mysqli_error($link));
                    $_SESSION["err"] = true;
                    $_SESSION["errdiv"] = '<div class="card" style="background-color: #f8d7da;border-color: #f5c6cb;color: #721c24;"><div class="card-body">Что-то пошло не так :(</div></div><p></p>';
                    header("location: /lkabedit.html");
                }

            } else {
                error_log(mysqli_error($link));
                $link->close();
                $_SESSION["err"] = true;
                $_SESSION["errdiv"] = '<div class="card" style="background-color: #f8d7da;border-color: #f5c6cb;color: #721c24;"><div class="card-body">Что-то пошло не так :(</div></div><p></p>';
                header("location: /lkabedit.html");
            }

        } else {
            error_log(mysqli_error($link));
            $link->close();
            $_SESSION["err"] = true;
            $_SESSION["errdiv"] = '<div class="card" style="background-color: #f8d7da;border-color: #f5c6cb;color: #721c24;"><div class="card-body">Что-то пошло не так :(</div></div><p></p>';
            header("location: /lkabedit.html");
        }
        mysqli_stmt_close($stmt);
        $link->close();
    } else {
        error_log(mysqli_error($link));
        $link->close();
        $_SESSION["err"] = true;
        $_SESSION["errdiv"] = '<div class="card" style="background-color: #f8d7da;border-color: #f5c6cb;color: #721c24;"><div class="card-body">Что-то пошло не так :(</div></div><p></p>';
        header("location: /lkabedit.html");
        exit();
    }
} else {
    header("location: /main.html");
    exit();
}