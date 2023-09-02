<?php

error_reporting(0);

define('SERVER', '127.0.0.1');
define('USER', 'root');
define('PASS', 'root');
define('DB', 'kata_score');

include './Objects/Competition.php';
include './Objects/Round.php';

if (isset($_POST["name"]) && isset($_POST["teamType"]) && isset($_POST["ageRange"]) && isset($_POST["gender"])) {
    $name = $_POST["name"];
    $teamType = $_POST["teamType"];
    $ageRange = $_POST["ageRange"];
    $gender = $_POST["gender"];

    $date = date('Y-m-d');

    $competition = new Competition('activa', $date, $teamType, $name, $ageRange, $gender);
    echo $competition->enterCompetition();

    $round = new Round(1, $competition->getId());
    $round->enterRound();
} else {
    http_response_code(400);
    echo json_encode(array("error" => "Ingrese los datos"));
}
