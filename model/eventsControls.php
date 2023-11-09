<?php

include_once "./Objects/DataBase.php";

if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = 'es';
}

error_reporting(0);

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $connection = mysqli_connect(SERVER, USER, PASS, DB);

    if (!$connection) {
        http_response_code(500);
        echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
    }

    $stmt = "SELECT * FROM competencia WHERE id_evento = $id";

    $competitions = mysqli_query($connection, $stmt);

    if (!$competitions) {
        http_response_code(500);
        echo json_encode(array("error" => "Error: " . $stmt));
        die();
    }

    while ($competition = $competitions->fetch_assoc()) {

        $competitionID = $competition['id_competencia'];

        $stmt = $connection->prepare("DELETE FROM realiza WHERE id_evento = ?");
        $stmt->bind_param("i", $competitionID);
        $response = $stmt->execute();
        if (!$response) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt->error));
        }
        $stmt->close();

        $stmt = $connection->prepare("DELETE FROM participa WHERE id_competencia = ?");
        $stmt->bind_param("i", $competitionID);
        $response = $stmt->execute();
        if (!$response) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt->error));
        }
        $stmt->close();

        $stmt = $connection->prepare("DELETE FROM puntua WHERE id_competencia = ?");
        $stmt->bind_param("i", $competitionID);
        $response = $stmt->execute();
        if (!$response) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt->error));
        }
        $stmt->close();

        $connection->query("SET FOREIGN_KEY_CHECKS=0");
        $stmt = $connection->prepare("DELETE FROM compite WHERE id_competencia = ?");
        $stmt->bind_param("i", $competitionID);
        $response = $stmt->execute();
        if (!$response) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt->error));
        }
        $stmt->close();
        $connection->query("SET FOREIGN_KEY_CHECKS=1");

        $stmt = $connection->prepare("DELETE FROM pertenece WHERE id_competencia = ?");
        $stmt->bind_param("i", $competitionID);
        $response = $stmt->execute();
        if (!$response) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt->error));
        }
        $stmt->close();

        $stmt = $connection->prepare("DELETE FROM pool WHERE id_competencia = ?");
        $stmt->bind_param("i", $competitionID);
        $response = $stmt->execute();
        if (!$response) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt->error));
        }
        $stmt->close();

        $connection->query("SET FOREIGN_KEY_CHECKS=0");

        $stmt = $connection->prepare("DELETE FROM ronda WHERE id_competencia = ?");
        $stmt->bind_param("i", $competitionID);
        $response = $stmt->execute();
        if (!$response) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt->error));
        }
        $stmt->close();

        $connection->query("SET FOREIGN_KEY_CHECKS=1");

        $stmt = $connection->prepare("DELETE FROM competencia WHERE id_competencia = ?");
        $stmt->bind_param("i", $competitionID);
        $response = $stmt->execute();
        if (!$response) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt->error));
        }
        $stmt->close();
    }

    $stmt = $connection->prepare("DELETE FROM evento WHERE id_evento = ?");

    if ($lang == "es") {
        $message = "Evento eliminada con exito";
    } else {
        $message = "Event removed successfully";
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
