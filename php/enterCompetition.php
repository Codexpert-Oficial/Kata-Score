<?php

error_reporting(0);

include './Objects/Competition.php';

if (isset($_POST["name"]) && isset($_POST["teamType"]) && isset($_POST["ageRange"]) && isset($_POST["gender"])) {
    $name = $_POST["name"];
    $teamType = $_POST["teamType"];
    $ageRange = $_POST["ageRange"];
    $gender = $_POST["gender"];

    $date = date('Y-m-d');

    $competition = new Competition('activa', $date, $teamType, $name, $ageRange, $gender);
    echo $competition->enterCompetition();
} else {
    http_response_code(400);
    echo json_encode(array("error" => "Ingrese los datos"));
}
