<?php

error_reporting(0);

define('SERVER', '127.0.0.1');
define('USER', 'root');
define('PASS', 'root');
define('DB', 'kata_score');

if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = "es";
}

if (isset($_POST['user']) && isset($_POST['password'])) {

    $user = $_POST['user'];
    $password = $_POST['password'];

    $connection = mysqli_connect(SERVER, USER, PASS, DB);

    if (!$connection) {
        http_response_code(500);
        echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
    }

    $stmt = "SELECT * FROM tecnico WHERE usuario_tecnico = '$user' AND clave_tecnico = '$password'";
    $response = mysqli_query($connection, $stmt);

    if (!$response) {
        http_response_code(500);
        echo json_encode(array("error" => "Error: " . $stmt));
    } else {
        if ($response->num_rows <= 0) {
            http_response_code(400);
            if ($lang == "es") {
                echo json_encode(array("error" => "Usuario no registrado"));
            } else {
                echo json_encode(array("error" => "User not registered"));
            }
        }
    }
} else {
    http_response_code(400);
    if ($lang == "es") {
        echo json_encode(array("error" => "Ingrese los datos"));
    } else {
        echo json_encode(array("error" => "Enter the data"));
    }
}
