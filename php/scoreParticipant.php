<?php

session_start();

include './Objects/ParticipantsArray.php';
include './Objects/ScoresArray.php';

$currentParticipant = unserialize($_SESSION["currentParticipant"]);
$judgeNumber = $_POST["number"];
$scoreValue = $_POST["score"];
$participantCi = $currentParticipant->getCi();
$scores = new ScoresArray($participantCi);

$score = new Score($scoreValue, $participantCi, $judgeNumber . PHP_EOL);

echo $scores->enterScore($score);
