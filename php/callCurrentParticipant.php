<?php

session_start();

if (isset($_SESSION["currentParticipant"])) {
    $_SESSION["displayParticipant"] = true;
    $_SESSION["displayParticipantScore"] = false;
} else {
    echo "Establesca un participante actual";
}
