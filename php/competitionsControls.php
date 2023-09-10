<?php

define('SERVER', '127.0.0.1');
define('USER', 'root');
define('PASS', 'root');
define('DB', 'kata_score');

error_reporting(0);

if (isset($_POST['id']) && isset($_POST['action'])) {
    $id = $_POST['id'];
    $action =  $_POST['action'];

    $connection = mysqli_connect(SERVER, USER, PASS, DB);

    if (!$connection) {
        http_response_code(500);
        echo json_encode(array("error" => "Error de conexion: " . mysqli_connect_error()));
    }

    switch ($action) {
        case 'delete':
            $stmt = $connection->prepare("DELETE FROM participa WHERE id_competencia = ?");
            $stmt->bind_param("i", $id);
            $response = $stmt->execute();
            if (!$response) {
                http_response_code(500);
                echo json_encode(array("error" => "Error: " . $stmt->error));
            } else {
                echo $message;
            }
            $stmt->close();

            $stmt = $connection->prepare("DELETE FROM puntua WHERE id_competencia = ?");
            $stmt->bind_param("i", $id);
            $response = $stmt->execute();
            if (!$response) {
                http_response_code(500);
                echo json_encode(array("error" => "Error: " . $stmt->error));
            } else {
                echo $message;
            }
            $stmt->close();

            $stmt = $connection->prepare("DELETE FROM compite WHERE id_competencia = ?");
            $stmt->bind_param("i", $id);
            $response = $stmt->execute();
            if (!$response) {
                http_response_code(500);
                echo json_encode(array("error" => "Error: " . $stmt->error));
            } else {
                echo $message;
            }
            $stmt->close();

            $stmt = $connection->prepare("DELETE FROM pertenece WHERE id_competencia = ?");
            $stmt->bind_param("i", $id);
            $response = $stmt->execute();
            if (!$response) {
                http_response_code(500);
                echo json_encode(array("error" => "Error: " . $stmt->error));
            } else {
                echo $message;
            }
            $stmt->close();

            $stmt = $connection->prepare("DELETE FROM pool WHERE id_competencia = ?");
            $stmt->bind_param("i", $id);
            $response = $stmt->execute();
            if (!$response) {
                http_response_code(500);
                echo json_encode(array("error" => "Error: " . $stmt->error));
            } else {
                echo $message;
            }
            $stmt->close();

            $stmt = $connection->prepare("DELETE FROM ronda WHERE id_competencia = ?");
            $stmt->bind_param("i", $id);
            $response = $stmt->execute();
            if (!$response) {
                http_response_code(500);
                echo json_encode(array("error" => "Error: " . $stmt->error));
            } else {
                echo $message;
            }
            $stmt->close();
            $stmt = $connection->prepare("DELETE FROM competencia WHERE id_competencia = ?");
            $message = "Competencia eliminada con exito";
            break;

        case 'activate':

            $stmt = $connection->prepare("UPDATE competencia SET estado = 'activa' WHERE id_competencia = ?");
            $message = "Competencia activada con exito";
            break;

        case 'desactivate':

            $stmt = $connection->prepare("UPDATE competencia SET estado = 'cerrada' WHERE id_competencia = ?");
            $message = "Competencia cerrada con exito";
            break;
    }

    $stmt->bind_param("i", $id);
    $response = $stmt->execute();
    if (!$response) {
        http_response_code(500);
        echo json_encode(array("error" => "Error: " . $stmt->error));
    } else {
        echo $message;
    }
    $stmt->close();
} else {
    http_response_code(400);
    echo json_encode(array("error" => "Ingrese los datos"));
}
