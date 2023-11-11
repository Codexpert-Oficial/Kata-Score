<?php

error_reporting(0);

include_once "./Objects/DataBase.php";

if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = "es";
}

include_once './Objects/School.php';

if (isset($_POST["name"]) && isset($_POST['technique'])) {
    $name = $_POST["name"];
    $technique = $_POST["technique"];

    $competition = new School($name, $technique);
    echo $competition->enterSchool();

    $round = new Round(1, $competition->getId());
    $round->enterRound();
} else {
    http_response_code(400);
    if ($lang == "es") {
        echo json_encode(array("error" => "Ingrese los datos"));
    } else {
        echo json_encode(array("error" => "Enter the data"));
    }
}
