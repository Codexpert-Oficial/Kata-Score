<?php

session_start();

include "./Objects/ParticipantsArray.php";

if (isset($_SESSION["displayParticipant"])) {

    if ($_SESSION["displayParticipant"]) {
        $participant = unserialize($_SESSION["currentParticipant"]);

        if (isset($_SESSION["displayParticipantScore"])) {
            if ($_SESSION["displayParticipantScore"]) {
                echo "<p> Puntaje: " . $_SESSION["total"] . "</p>";
            }
        }

        echo "<p> Categoria: " . $participant->getAgeRange() . " - " . $participant->getGender() . "</p>";
        if (isset($_SESSION['round'])) {
            echo "<p> Ronda " . $_SESSION['round'] . "</p>";
        } else {
            echo "<p> Ronda 1</p>";
            $_SESSION['round'] = 1;
        }

        echo "<p>" . $participant->getLastName() . ", " . $participant->getName() . "</p>";
        echo "<p>" . $participant->getKataName() . "</p>";
    }
}

if (isset($_SESSION["displayClassified"])) {

    if ($_SESSION["displayClassified"]) {

        $participants = new ParticipantsArray();
        $pool = $_SESSION["poolDisplay"];

        echo "<h1> Clasificados </h1>";

        $participants->classifiedParticipants($pool);
    }
}
