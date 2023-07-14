<?php

session_start();

include './Objects/ScoresArray.php';
include './Objects/ParticipantsArray.php';

$scores = new ScoresArray(0);
$scores->removeScores();

if (isset($_SESSION["round"])) {
    $_SESSION["round"]++;
    echo "Ronda actual: " . $_SESSION["round"];
} else {
    $_SESSION["round"] = 2;
    echo "Ronda actual: " . $_SESSION["round"];
}
