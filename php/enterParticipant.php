<?php

include 'Participant.php';
include 'KatasArray.php';

if (isset($_POST["name"]) && isset($_POST["lastName"]) && isset($_POST["kata"]) && isset($_POST["ageRange"]) && isset($_POST["gender"])) {
    $name = $_POST["name"];
    $lastName = $_POST["lastName"];
    $idKata = $_POST["kata"];
    $ageRange = $_POST["ageRange"];
    $gender = $_POST["gender"];

    $category = new Category($ageRange, $gender);
    $katas = new KatasArray();
    $kata = $katas->getKata($idKata);

    $participant = new Participant($name, $lastName, $category, $kata);

    var_dump($participant);
} else {
    echo "Ingrese los datos";
}
