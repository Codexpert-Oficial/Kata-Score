<?php

include "./Objects/ParticipantsArray.php";

$participants = new ParticipantsArray();

if (isset($_POST["ci"])) {

    $ci = $_POST["ci"];

    echo $participants->removeParticipant($ci);
} else {
    echo "Ingrese los datos";
}
