<?php

define('SERVER', '127.0.0.1');
define('USER', 'root');
define('PASS', 'root');
define('DB', 'kata_score');

if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = 'es';
}

error_reporting(0);

include_once './Objects/Score.php';
include_once './Objects/Round.php';

if (isset($_POST['score']) && isset($_POST['judge']) && isset($_POST['competition']) && isset($_POST['round'])) {

    $scoreValue = $_POST['score'];
    $judge = $_POST['judge'];
    $competition = $_POST['competition'];
    $numRound = $_POST['round'];

    $round = new Round($numRound, $competition);

    $participant = $round->getActiveParticipant();
    if (!$participant) {
        http_response_code(400);
        if ($lang == "es") {
            echo json_encode(array("error" => "No hay ningun participante activo"));
        } else {
            echo json_encode(array("error" => "There is not an active participant"));
        }
    } else {
        $score = new Score($scoreValue, $participant['ci'], $judge, $competition, $numRound);
        echo $score->enterScore();
    }
} else {

    http_response_code(400);
    if ($lang == "es") {
        echo json_encode(array("error" => "Ingrese los datos"));
    } else {
        echo json_encode(array("error" => "Enter the data"));
    }
}
