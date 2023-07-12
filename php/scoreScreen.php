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
        echo "<p>" . $participant->getLastName() . ", " . $participant->getName() . "</p>";
        echo "<p>" . $participant->getKataName() . "</p>";
    }
}
