<?php
session_start();

error_reporting(0);

define('SERVER', '127.0.0.1');
define('USER', 'root');
define('PASS', 'root');
define('DB', 'kata_score');

include_once './Objects/Participates.php';

if (isset($_POST['user']) && isset($_POST['number']) && isset($_SESSION['competition'])) {
    $user = $_POST['user'];
    $number = $_POST['number'];
    $competition = $_SESSION['competition'];

    $participates = new Participates($user, $competition, $number);
    echo $participates->enterParticipates();
} else {
    http_response_code(400);
    echo json_encode(array("error" => "Ingrese los datos"));
}
