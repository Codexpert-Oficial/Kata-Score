<?php

session_start();

if (isset($_SESSION["currentParticipant"])) {
    $_SESSION["displayParticipant"] = true;
} else {
    echo "Establesca un participante actual";
}
