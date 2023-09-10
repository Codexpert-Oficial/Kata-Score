<?php

error_reporting(0);

define('SERVER', '127.0.0.1');
define('USER', 'root');
define('PASS', 'root');
define('DB', 'kata_score');

include './Objects/Judge.php';

if (isset($_POST["name"]) && isset($_POST["lastName"]) && isset($_POST["user"]) && isset($_POST["password"])) {
    $name = $_POST["name"];
    $lastName = $_POST["lastName"];
    $user = $_POST["user"];
    $password = $_POST["password"];

    $judge = new Judge($name, $lastName, $user, $password);
    echo $judge->enterJudge();
} else {
    http_response_code(400);
    echo json_encode(array("error" => "Ingrese los datos"));
}