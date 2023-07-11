<?php

include 'ParticipantsArray.php';
include 'KatasArray.php';

if (isset($_POST["ci"]) && isset($_POST["name"]) && isset($_POST["lastName"]) && isset($_POST["kata"]) && isset($_POST["ageRange"]) && isset($_POST["gender"])) {

    $ci = $_POST["ci"];
    $name = $_POST["name"];
    $lastName = $_POST["lastName"];
    $idKata = $_POST["kata"];
    $ageRange = $_POST["ageRange"];
    $gender = $_POST["gender"];

    $participant = new Participant($ci, $name, $lastName, $ageRange, $gender, $idKata . PHP_EOL);
    $participants = new ParticipantsArray();
    echo $participants->enterParticipant($participant);
} else {
    echo "Ingrese los datos";
}
