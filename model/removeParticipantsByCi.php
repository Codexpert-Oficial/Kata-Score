<?php
session_start();

error_reporting(0);

include_once "./Objects/Round.php";
include_once "./Objects/Competition.php";

define('SERVER', '127.0.0.1');
define('USER', 'root');
define('PASS', 'root');
define('DB', 'kata_score');

if (isset($_POST["ci"]) && isset($_SESSION['competition'])) {

    $ci = $_POST["ci"];
    $competitionID = $_SESSION['competition'];

    $connection = mysqli_connect(SERVER, USER, PASS, DB);

    if (!$connection) {
        http_response_code(500);
        echo json_encode(array("error" => "Error de conexion: " . mysqli_connect_error()));
    }
    $stmt = "SELECT * FROM competencia WHERE id_competencia = $competitionID";

    $response = mysqli_query($connection, $stmt);

    $competitionInfo = $response->fetch_assoc();

    $competition = new Competition($competitionInfo['estado'], $competitionInfo['fecha'], $competitionInfo['tipo_equipos'], $competitionInfo['nombre'], $competitionInfo['rango_etario'], $competitionInfo['sexo']);

    $competition->setId($competitionID);
    $numRound = $competition->getLastRound();

    $round = new Round($numRound, $competitionID);

    if ($round->participantExists($ci)) {
        $stmt = $connection->prepare("DELETE FROM realiza WHERE ci = ? AND num_ronda = ? AND id_competencia = ?");
        $stmt->bind_param("iii", $ci, $numRound, $competitionID);
        if (!$stmt->execute()) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt->error));
        }
        $stmt->close();

        $stmt = $connection->prepare("DELETE FROM puntua WHERE ci = ? AND num_ronda = ? AND id_competencia = ?");
        $stmt->bind_param("iii", $ci, $numRound, $competitionID);
        if (!$stmt->execute()) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt->error));
        }
        $stmt->close();

        $stmt = $connection->prepare("DELETE FROM pertenece WHERE ci = ? AND num_ronda = ? AND id_competencia = ?");
        $stmt->bind_param("iii", $ci, $numRound, $competitionID);
        if (!$stmt->execute()) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt->error));
        }
        $stmt->close();

        $connection->query("SET FOREIGN_KEY_CHECKS=0");
        $stmt = $connection->prepare("DELETE FROM compite WHERE ci = ? AND num_ronda = ? AND id_competencia = ?");
        $stmt->bind_param("iii", $ci, $numRound, $competitionID);
        if ($stmt->execute()) {
            echo "Participante eliminado con exito";
        } else {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt->error));
        }
        $stmt->close();

        $connection->query("SET FOREIGN_KEY_CHECKS=1");
    }
} else {
    http_response_code(400);
    echo json_encode(array("error" => "Ingrese los datos"));
}
