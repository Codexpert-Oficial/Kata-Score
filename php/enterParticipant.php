<?php

error_reporting(0);

include './Objects/ParticipantsArray.php';

if (isset($_POST["ci"]) && isset($_POST["name"]) && isset($_POST["lastName"]) && isset($_POST["kata"]) && isset($_POST["ageRange"]) && isset($_POST["gender"])) {
    $ci = $_POST["ci"];
    $name = $_POST["name"];
    $lastName = $_POST["lastName"];
    $idKata = $_POST["kata"];
    $ageRange = $_POST["ageRange"];
    $gender = $_POST["gender"];

    $participant = new Participant($ci, $name, $lastName, $ageRange, $gender, $idKata, 0);
    $participants = new ParticipantsArray();
    echo $participants->enterParticipant($participant);
} else {
    http_response_code(400);
    echo json_encode(array("error" => "Ingrese los datos"));
}
