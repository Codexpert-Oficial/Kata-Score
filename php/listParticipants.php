<?php

include "./Objects/ParticipantsArray.php";

$participants = new ParticipantsArray();

echo "<table border =1>";

$participants->listParticipants();

echo "</table>";
