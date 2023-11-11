<?php

session_start();

error_reporting(0);

include_once "./Objects/DataBase.php";

if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = "es";
}

include_once './Objects/Competition.php';
include_once './Objects/Round.php';

if (isset($_POST["name"]) && isset($_POST["ageRange"]) && isset($_POST["gender"]) && isset($_POST['modality']) && isset($_POST['category']) && isset($_SESSION['event'])) {
    $name = $_POST["name"];
    $ageRange = $_POST["ageRange"];
    $gender = $_POST["gender"];
    $eventID = $_SESSION['event'];
    $modality = $_POST['modality'];
    $category = $_POST['category'];

    $date = date('Y-m-d');

    $competition = new Competition('activa', $date, $name, $ageRange, $gender, $eventID);
    echo $competition->enterCompetition();
    $competition->setModality($modality, $category);

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
