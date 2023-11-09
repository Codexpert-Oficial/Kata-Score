<?php

session_start();

error_reporting(0);

include_once './Objects/Competition.php';
include_once './Objects/Round.php';

include_once "./Objects/DataBase.php";

if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = 'es';
}

if (isset($_SESSION['competition'])) {
    $competitionID = $_SESSION['competition'];

    $connection = mysqli_connect(SERVER, USER, PASS, DB);

    if (!$connection) {
        http_response_code(500);
        echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
    }
    $stmt = "SELECT * FROM competencia WHERE id_competencia = $competitionID";

    $response = mysqli_query($connection, $stmt);

    $competitionInfo = $response->fetch_assoc();

    $competition = new Competition($competitionInfo['estado'], $competitionInfo['fecha'], $competitionInfo['nombre'], $competitionInfo['rango_etario'], $competitionInfo['sexo'], $competitionInfo['id_evento']);
    $competition->setId($competitionID);

    $numRound = $competition->getLastRound();

    $round = new Round($numRound, $competitionID);

    $participant = $round->getActiveParticipant();

    if (!$participant) {
        http_response_code(400);
        if ($lang == "es") {
            echo json_encode(array("error" => "No hay ningun participante activo"));
        } else {
            echo json_encode(array("error" => "There is not an active participant"));
        }
    } else {
        $_SESSION["displayParticipant"] = true;
        $_SESSION["displayParticipantScore"] = false;
        $_SESSION["displayClassified"] = false;
        if ($lang == "es") {
            echo "Acción realizada con éxito";
        } else {
            echo "Action done successfully";
        }
    }
} else {
    http_response_code(400);
    if ($lang == "es") {
        echo json_encode(array("error" => "Seleccione una competencia"));
    } else {
        echo json_encode(array("error" => "Select a competition"));
    }
}
