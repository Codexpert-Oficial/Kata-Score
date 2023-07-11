<?php

session_start();

include "./Objects/ParticipantsArray.php";

$participants = new ParticipantsArray();

if (isset($_POST["ci"])) {
    $ci = $_POST["ci"];

    $participant =  $participants->getParticipant($ci);
    $_SESSION["currentParticipant"] = serialize($participant);
} else {
    echo "Ingrese los datos";
}
