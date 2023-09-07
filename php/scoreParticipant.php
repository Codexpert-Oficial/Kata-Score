<?php

define('SERVER', '127.0.0.1');
define('USER', 'root');
define('PASS', 'root');
define('DB', 'kata_score');

include './Objects/Score.php';
include './Objects/Round.php';

if (isset($_POST['score']) && isset($_POST['judge']) && isset($_POST['competition']) && isset($_POST['round'])) {

    $scoreValue = $_POST['score'];
    $judge = $_POST['judge'];
    $competition = $_POST['competition'];
    $numRound = $_POST['round'];

    $round = new Round($numRound, $competition);

    $participant = $round->getActiveParticipant();
    $score = new Score($scoreValue, $participant['ci'], $judge, $competition, $numRound);
    echo $score->enterScore();
} else {

    http_response_code(400);
    echo json_encode(array("error" => "Ingrese los datos"));
}
