<?php

session_start();

include "./Objects/ParticipantsArray.php";

$participants = new ParticipantsArray();

if (isset($_POST["ci"])) {
    $ci = $_POST["ci"];

    $participant =  $participants->getParticipant($ci);
    if (isset($participant)) {
        $_SESSION["currentParticipant"] = serialize($participant);
    } else {
        echo "Usuario no registrado";
    }
} else {
    echo "Ingrese los datos";
}
