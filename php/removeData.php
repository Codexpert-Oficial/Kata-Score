<?php

session_start();

include './Objects/ParticipantsArray.php';

$participants = new ParticipantsArray();
$scores = new ScoresArray(0);

$participants->removeParticipants();

$scores->removeScores();

unset($_SESSION["currentParticipant"]);

unset($_SESSION["poolDisplay"]);

$_SESSION["round"] = 1;
