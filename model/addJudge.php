<?php
session_start();

error_reporting(0);

include_once "./Objects/DataBase.php";

if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = 'es';
}

include_once './Objects/Participates.php';

if (isset($_POST['user']) && isset($_POST['number']) && isset($_SESSION['competition'])) {
    $user = $_POST['user'];
    $number = $_POST['number'];
    $competition = $_SESSION['competition'];

    $participates = new Participates($user, $competition, $number);
    echo $participates->enterParticipates();
} else {
    http_response_code(400);
    if ($lang == "es") {
        echo json_encode(array("error" => "Ingrese los datos"));
    } else {
        echo json_encode(array("error" => "Enter the data"));
    }
}
