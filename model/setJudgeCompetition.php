<?php
session_start();

error_reporting(0);

if (isset($_POST["judgeCompetition"])) {
    $competition = $_POST['judgeCompetition'];
    $_SESSION['judgeCompetition'] = $competition;
}
