<?php session_start();

error_reporting(0);

include_once './Objects/Performs.php';

include_once "./Objects/DataBase.php";

if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = 'es';
}

if (isset($_POST['participant']) && isset($_POST['kata']) && isset($_POST['round']) && isset($_SESSION['competition'])) {
    $ci = $_POST['participant'];
    $kata = $_POST['kata'];
    $round = $_POST['round'];
    $competition = $_SESSION['competition'];

    $performs = new Performs($ci, $competition, $round, $kata);
    echo $performs->enterPerforms();
} else {
    http_response_code(400);
    if ($lang == "es") {
        echo json_encode(array("error" => "Ingrese los datos"));
    } else {
        echo json_encode(array("error" => "Enter the data"));
    }
}
