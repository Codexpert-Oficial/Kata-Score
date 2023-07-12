<?php

session_start();

include './Objects/ScoresArray.php';
include './Objects/ParticipantsArray.php';

if (isset($_SESSION["currentParticipant"])) {
    $participant = unserialize($_SESSION["currentParticipant"]);
    $scores = new ScoresArray($participant->getCi());
    if (count($scores->getScores()) == 5) {
        $_SESSION["displayParticipant"] = true;
        $_SESSION["displayParticipantScore"] = true;
        $total = $scores->calcTotal();
        $_SESSION["total"] = $total;
    } else {
        echo "No todos los jueces han puntuado al participante";
    }
} else {
    echo "Establesca un participante actual";
}
