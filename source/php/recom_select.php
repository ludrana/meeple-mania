<?php
$conn = new mysqli("localhost", "root", "", "meeple_mania");
$html = "";
if ($conn->connect_error) {
    error_log($conn->connect_error);
    header("Location: /418.html");
    exit();
}
try {
    $sql = "SELECT MIN(players_min) AS min, MAX(players_max) AS max FROM games;";
    if ($result = $conn->query($sql)) {
        foreach ($result as $row) {
            $min = $row["min"];
            $max = $row["max"];
            $html .= '<div class="recform"><p></p><p></p><div class="spacer justify-content-center flex-grow-1 d-flex align-items-center"><h1>Рекомендатор настольных игр</h1></div><form id="recommendationForm"><label for="players">Количество игроков: <span id="playersValue">8</span></label><input type="range" class="recom-input" id="players" name="players" min="' . $min . '" max="' . $max . '" oninput="updatePlayerCount()" required>';
        }
    }
    $sql = "SELECT * FROM genres;";
    $html .= '<label for="genre">Жанр:</label><select id="genre" name="genre" class="form-select"><option value="0">Не выбрано</option>';
    if ($result = $conn->query($sql)) {
        foreach ($result as $row) {
            $gname = $row["genre_name"];
            $gid = $row["genre_id"];
            $html .= '<option value="' . $gid . '">' . $gname . '</option>';
        }
    }
    $html .= '</select>';
    $sql = "SELECT * FROM publishers;";
    $html .= '<label for="publ">Издатель:</label><select id="publ" name="publ" class="form-select"><option value="0">Не выбрано</option>';
    if ($result = $conn->query($sql)) {
        foreach ($result as $row) {
            $pname = $row["publisher_name"];
            $pid = $row["publisher_id"];
            $html .= '<option value="' . $pid . '">' . $pname . '</option>';
        }
    }
    $html .= '</select>';
    $sql = "SELECT MIN(time_min) AS min, MAX(time_max) AS max FROM games;";
    if ($result = $conn->query($sql)) {
        foreach ($result as $row) {
            $min = $row["min"];
            $max = $row["max"];
            $html .= '<label for="time">Время игры (в минутах): <span id="timeValue">135</span></label><input type="range" class="recom-input" id="time" name="time" min="' . $min . '" max="' . $max . '" step="15" oninput="updatePlayTime()" required>';
        }
    }
    $sql = "SELECT MIN(player_age) AS min, MAX(player_age) AS max FROM games;";
    $html .= '<label for="age">Выберите возрастную категорию:</label><select id="age" name="age" class="form-select"><option value="0">Не выбрано</option>';
    if ($result = $conn->query($sql)) {
        foreach ($result as $row) {
            $min = $row["min"];
            $max = $row["max"];
            for ($i = $min; $i <= $max; $i++) {
                $html .= '<option value="' . $i . '">' . $i . '+</option>';
            }
        }
    }
    $html .= '</select><div class="d-flex align-items-center justify-content-center"><button type="button" class="btn btn-warning btn-lg p-3 m-3 w-50" onclick="getRecommendedGames()"> Получить рекомендацию </button></div><div id="result"></div></form></div>';
} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: /418.html");
    exit();
} finally {
    $conn->close();
}