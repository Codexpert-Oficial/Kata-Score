<?php

define('SERVER', '127.0.0.1');
define('USER', 'root');
define('PASS', 'root');
define('DB', 'kata_score');

if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = 'es';
}

error_reporting(0);

if (isset($_POST['id']) && isset($_POST['action'])) {
    $id = $_POST['id'];
    $action =  $_POST['action'];

    $connection = mysqli_connect(SERVER, USER, PASS, DB);

    if (!$connection) {
        http_response_code(500);
        echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
    }

    switch ($action) {
        case 'delete':
            $stmt = $connection->prepare("DELETE FROM realiza WHERE id_competencia = ?");
            $stmt->bind_param("i", $id);
            $response = $stmt->execute();
            if (!$response) {
                http_response_code(500);
                echo json_encode(array("error" => "Error: " . $stmt->error));
            }
            $stmt->close();

            $stmt = $connection->prepare("DELETE FROM participa WHERE id_competencia = ?");
            $stmt->bind_param("i", $id);
            $response = $stmt->execute();
            if (!$response) {
                http_response_code(500);
                echo json_encode(array("error" => "Error: " . $stmt->error));
            }
            $stmt->close();

            $stmt = $connection->prepare("DELETE FROM puntua WHERE id_competencia = ?");
            $stmt->bind_param("i", $id);
            $response = $stmt->execute();
            if (!$response) {
                http_response_code(500);
                echo json_encode(array("error" => "Error: " . $stmt->error));
            }
            $stmt->close();

            $connection->query("SET FOREIGN_KEY_CHECKS=0");
            $stmt = $connection->prepare("DELETE FROM compite WHERE id_competencia = ?");
            $stmt->bind_param("i", $id);
            $response = $stmt->execute();
            if (!$response) {
                http_response_code(500);
                echo json_encode(array("error" => "Error: " . $stmt->error));
            }
            $stmt->close();
            $connection->query("SET FOREIGN_KEY_CHECKS=1");

            $stmt = $connection->prepare("DELETE FROM pertenece WHERE id_competencia = ?");
            $stmt->bind_param("i", $id);
            $response = $stmt->execute();
            if (!$response) {
                http_response_code(500);
                echo json_encode(array("error" => "Error: " . $stmt->error));
            }
            $stmt->close();

            $stmt = $connection->prepare("DELETE FROM pool WHERE id_competencia = ?");
            $stmt->bind_param("i", $id);
            $response = $stmt->execute();
            if (!$response) {
                http_response_code(500);
                echo json_encode(array("error" => "Error: " . $stmt->error));
            }
            $stmt->close();

            $connection->query("SET FOREIGN_KEY_CHECKS=0");

            $stmt = $connection->prepare("DELETE FROM ronda WHERE id_competencia = ?");
            $stmt->bind_param("i", $id);
            $response = $stmt->execute();
            if (!$response) {
                http_response_code(500);
                echo json_encode(array("error" => "Error: " . $stmt->error));
            }
            $stmt->close();

            $connection->query("SET FOREIGN_KEY_CHECKS=1");

            $stmt = $connection->prepare("DELETE FROM competencia WHERE id_competencia = ?");
            if ($lang == "es") {
                $message = "Competencia eliminada con exito";
            } else {
                $message = "Competition removed successfully";
            }

            break;

        case 'activate':

            $stmt = $connection->prepare("UPDATE competencia SET estado = 'activa' WHERE id_competencia = ?");
            if ($lang == "es") {
                $message = "Competencia activada con exito";
            } else {
                $message = "Competition activated successfully";
            }

            break;

        case 'desactivate':

            $stmt = $connection->prepare("UPDATE competencia SET estado = 'cerrada' WHERE id_competencia = ?");
            if ($lang == "es") {
                $message = "Competencia cerrada con exito";
            } else {
                $message = "Competition closed successfully";
            }

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
    if ($lang == "es") {
        echo json_encode(array("error" => "Ingrese los datos"));
    } else {
        echo json_encode(array("error" => "Enter the data"));
    }
}
