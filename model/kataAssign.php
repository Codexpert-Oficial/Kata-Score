<?php

session_start();

error_reporting(0);

include_once './Objects/Performs.php';

define('SERVER', '127.0.0.1');
define('USER', 'root');
define('PASS', 'root');
define('DB', 'kata_score');

if (isset($_POST['participant']) && isset($_POST['kata']) && isset($_POST['round']) && isset($_SESSION['competition'])) {
    $ci = $_POST['participant'];
    $kata = $_POST['kata'];
    $round = $_POST['round'];
    $competition = $_SESSION['competition'];

    $performs = new Performs($ci, $competition, $round, $kata);
    echo $performs->enterPerforms();
} else {
    http_response_code(400);
    echo json_encode(array("error" => "Ingrese los datos"));
}
