<?php

session_start();

include './Objects/ParticipantsArray.php';

if (isset($_POST["pool"])) {
    $_SESSION["poolDisplay"] = $_POST["pool"];
    $_SESSION["displayClassified"] = true;
    $_SESSION["displayParticipant"] = false;

    $participants = new ParticipantsArray();

    $cont = 0;

    foreach ($participants->getParticipants() as $participant) {
        $scores = new ScoresArray($participant->getCi());
        if (count($scores->getScores()) == 5) {
            $cont++;
        }
    }

    if ($cont == count($participants->getParticipants())) {
        $participants->orderByScore();
    } else {
        echo "No todos los participantes fueron ingresados";
    }
} else {
    echo "Ingrese los datos";
}
