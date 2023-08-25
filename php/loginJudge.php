<?php

session_start();

define('SERVER', '127.0.0.1');
define('USER', 'root');
define('PASS', 'root');
define('DB', 'kata_score');

if (isset($_POST['user']) && isset($_POST['password'])) {

    $user = $_POST['user'];
    $password = $_POST['password'];

    $connection = mysqli_connect(SERVER, USER, PASS, DB);

    if (!$connection) {
        http_response_code(500);
        echo json_encode(array("error" => "Error de conexion: " . mysqli_connect_error()));
    }

    $stmt = "SELECT * FROM juez WHERE usuario_juez = '$user' AND clave_juez = '$password'";
    $response = mysqli_query($connection, $stmt);

    if (!$response) {
        http_response_code(500);
        echo json_encode(array("error" => "Error al ingresar: " . $stmt));
    } else {
        if ($response->num_rows <= 0) {
            http_response_code(400);
            echo json_encode(array("error" => "Usuario no registrado"));
        } else {
            $_SESSION['judgeUser'] = $user;
        }
    }
} else {
    http_response_code(400);
    echo json_encode(array("error" => "Ingrese los datos"));
}
