<?php
session_start();

error_reporting(0);

define('SERVER', '127.0.0.1');
define('USER', 'root');
define('PASS', 'root');
define('DB', 'kata_score');

include './Objects/Participant.php';
include './Objects/Competes.php';
include './Objects/Performs.php';

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
    echo json_encode(array("error" => "Ingrese los datos"));
}
