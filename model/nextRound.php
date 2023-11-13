<?php session_start();

error_reporting(0);

include_once "./Objects/DataBase.php";

if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = 'es';
}

include_once './Objects/Competition.php';
include_once './Objects/Round.php';
include_once './Objects/Competes.php';

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

    if (!$competition->passRound()) {
        http_response_code(400);
        if ($lang == "es") {
            echo json_encode(array("error" => "No se pudo completar la accion"));
        } else {
            echo json_encode(array("error" => "The action couldn't be completed"));
        }
    } else {
        if ($lang == "es") {
            echo "Accion realizada con exito";
        } else {
            echo "Action done successfully";
        }
    }
}
