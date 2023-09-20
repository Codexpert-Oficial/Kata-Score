<?php
session_start();

error_reporting(0);

if (isset($_POST["scoreCompetition"])) {
    $competition = $_POST['scoreCompetition'];
    $_SESSION['scoreCompetition'] = $competition;
}
