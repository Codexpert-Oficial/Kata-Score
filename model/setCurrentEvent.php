<?php
session_start();

error_reporting(0);

if (isset($_POST["event"])) {
    $event = $_POST['event'];
    $_SESSION['event'] = $event;
}
