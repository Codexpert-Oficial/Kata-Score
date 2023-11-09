<?php

error_reporting(0);

include_once "./Objects/DataBase.php";

if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = "es";
}

include_once './Objects/Event.php';

if (isset($_POST["name"])) {
    $name = $_POST["name"];

    $competition = new Event($name);
    echo $competition->enterCompetition();
} else {
    http_response_code(400);
    if ($lang == "es") {
        echo json_encode(array("error" => "Ingrese los datos"));
    } else {
        echo json_encode(array("error" => "Enter the data"));
    }
}
