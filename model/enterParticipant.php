<?php
session_start();

error_reporting(0);

define('SERVER', '127.0.0.1');
define('USER', 'root');
define('PASS', 'root');
define('DB', 'kata_score');

if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = 'es';
}

include_once './Objects/Participant.php';
include_once './Objects/Competes.php';

if (isset($_POST["ci"]) && isset($_POST["name"]) && isset($_POST["lastName"]) && isset($_SESSION['competition'])) {
    $ci = $_POST["ci"];
    $name = $_POST["name"];
    $lastName = $_POST["lastName"];
    $competition = $_SESSION['competition'];

    $participant = new Participant($ci, $name, $lastName);
    $participant->enterParticipant();

    $competes = new Competes($ci, $competition, 1);
    echo $competes->enterCompetes();
} else {
    http_response_code(400);
    if ($lang == 'es') {
        echo json_encode(array("error" => "Ingrese los datos"));
    } else {
        echo json_encode(array("error" => "Enter the data"));
    }
}
