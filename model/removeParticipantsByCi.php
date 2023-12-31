<?php session_start();

error_reporting(0);

include_once "./Objects/Round.php";
include_once "./Objects/Competition.php";

include_once "./Objects/DataBase.php";

if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = 'es';
}

if (isset($_POST["ci"]) && isset($_SESSION['competition'])) {

    $ci = $_POST["ci"];
    $competitionID = $_SESSION['competition'];

    $connection = mysqli_connect(SERVER, USER, PASS, DB);

    if (!$connection) {
        http_response_code(500);
        echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
    }
    $stmt = "SELECT * FROM competencia WHERE id_competencia = $competitionID";

    $response = mysqli_query($connection, $stmt);

    $competitionInfo = $response->fetch_assoc();

    $competition = new Competition(
        $competitionInfo['estado'],
        $competitionInfo['fecha'],
        $competitionInfo['nombre'],
        $competitionInfo['rango_etario'],
        $competitionInfo['sexo'],
        $competitionInfo['id_evento']
    );

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
            if ($lang == "es") {
                echo "Participante quitado con exito";
            } else {
                echo "Participant removed successfully";
            }
        } else {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt->error));
        }
        $stmt->close();

        $connection->query("SET FOREIGN_KEY_CHECKS=1");
    }
} else {
    http_response_code(400);
    if ($lang == "es") {
        echo json_encode(array("error" => "Ingrese los datos"));
    } else {
        echo json_encode(array("error" => "Enter the data"));
    }
}
