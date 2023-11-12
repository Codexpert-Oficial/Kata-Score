<?php

session_start();

/* error_reporting(0); */

include_once "./Objects/DataBase.php";

if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = 'es';
}

include_once './Objects/Competition.php';

if (isset($_SESSION['competition'])) {
    $competitionID = $_SESSION['competition'];

    $connection = mysqli_connect(SERVER, USER, PASS, DB);

    if (!$connection) {
        http_response_code(500);
        echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
    }

    $stmt = "SELECT k.id_competencia idKarate, pk.id_competencia idPKarate FROM competencia c 
    LEFT JOIN karate k ON c.id_competencia = k.id_competencia
    LEFT JOIN `para-karate` pk ON c.id_competencia = pk.id_competencia 
    WHERE c.id_competencia = $competitionID";

    $response = mysqli_query($connection, $stmt);

    if (!$response) {
        http_response_code(500);
        echo json_encode(array("error" => "Error: " . $stmt));
    }

    $result = $response->fetch_assoc();

    if ($result['idPKarate'] != "") {

        if ($lang == "es") {
            echo "<input type='number' min=0 max=3 step=0.1 name='extraScore' class='input' required placeholder='Puntaje Extra'>";
        } else {
            echo "<input type='number' min=0 max=3 step=0.1 name='extraScore' class='input' required placeholder='Extra Score'>";
        }
    }
}
