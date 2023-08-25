<?php

define('SERVER', '127.0.0.1');
define('USER', 'root');
define('PASS', 'root');
define('DB', 'kata_score');

include './Objects/Participates.php';

if (isset($_POST['user']) && isset($_POST['number']) && isset($_POST['competition'])) {
    $user = $_POST['user'];
    $number = $_POST['number'];
    $competition = $_POST['competition'];

    $participates = new Participates($user, $competition, $number);
    echo $participates->enterParticipates();
} else {
    http_response_code(400);
    echo json_encode(array("error" => "Ingrese los datos"));
}
