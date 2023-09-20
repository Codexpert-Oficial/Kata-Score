<?php
session_start();

error_reporting(0);

if (isset($_POST["competition"])) {
    $competition = $_POST['competition'];
    $_SESSION['competition'] = $competition;
}
