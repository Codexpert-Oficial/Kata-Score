<?php

session_start();

/* error_reporting(0); */

include_once "./Objects/DataBase.php";
include_once "./Objects/Competition.php";
include_once "./Objects/Round.php";

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
        echo json_encode(array("error" => "Error de conexion: " . mysqli_connect_error()));
    }
    $stmt = "SELECT * FROM competencia WHERE id_competencia = $competitionID";

    $response = mysqli_query($connection, $stmt);

    $competitionInfo = $response->fetch_assoc();

    $competition = new Competition($competitionInfo['estado'], $competitionInfo['fecha'], $competitionInfo['nombre'], $competitionInfo['rango_etario'], $competitionInfo['sexo'], $competitionInfo['id_evento']);
    $competition->setId($competitionID);

    $numRound = $competition->getLastRound();
    $round = new Round($numRound, $competitionID);

    if ($round->allScored()) {
        if ($competition->isLastRound()) {
            $competition->getLastRoundClassifieds();

            $stmt = "SELECT COALESCE(puesto,0) puesto, nombre_competidor, apellido_competidor, SUM(puntaje) - MAX(puntaje) - MIN(puntaje) + COALESCE(puntaje_extra, 0) AS puntaje_final
            FROM compite c JOIN puntua p ON c.id_competencia = p.id_competencia AND c.num_ronda = p.num_ronda AND c.ci = p.ci
            JOIN competidor ON c.ci = competidor.ci
            WHERE c.id_competencia = $competitionID AND c.num_ronda = $numRound GROUP BY c.ci ORDER BY puesto";

            $participants = mysqli_query($connection, $stmt);

            if ($lang == "es") {
                echo "<h1 class='title'>Clasificados</h1><table class='table'>
                <thead> <th> Posicion </th> <th> Nombre </th> <th> Apellido </th> <th> Puntaje </th> </thead>
                <tbody>";
            } else {
                echo "<h1 class='title'>Classifieds</h1><table class='table'>
                <thead> <th> Position </th> <th> Name </th> <th> Last Name </th> <th> Score </th> </thead>
                <tbody>";
            }

            while ($participant = $participants->fetch_assoc()) {
                if ($participant['puesto'] != 0) {
                    echo "<tr><td>" . $participant['puesto'] . "</td><td>" . $participant['nombre_competidor'] . "</td><td>" . $participant['apellido_competidor'] . "</td><td>" . $participant['puntaje_final'] . "</td></tr>";
                }
            }
        } else {
            $round->setPositions();

            $pools = $round->getPools();

            while ($pool = $pools->fetch_assoc()) {

                $idPool = $pool['id_pool'];

                $stmt = "SELECT competidor.nombre_competidor, competidor.apellido_competidor, puntua.ci ,SUM(puntaje) - MAX(puntaje) - MIN(puntaje) + COALESCE(puntaje_extra, 0) AS puntaje_final
                    FROM puntua
                    JOIN pertenece ON pertenece.ci = puntua.ci AND pertenece.id_competencia = puntua.id_competencia AND pertenece.num_ronda = puntua.num_ronda
                    JOIN competidor ON puntua.ci = competidor.ci
                    WHERE pertenece.id_pool = $idPool
                    AND puntua.id_competencia = $competitionID
                    AND puntua.num_ronda = $numRound
                    GROUP BY puntua.ci
                    ORDER BY puntaje_final DESC";

                $participants = mysqli_query($connection, $stmt);

                if (!$participants) {
                    die("Error: " . $stmt);
                }

                if ($lang == "es") {
                    echo "<h2 class='title'> Pool " . $idPool . " </h2>
                    <table class='table'>
                    <thead> <th> Posicion </th> <th> Nombre </th> <th> Apellido </th> <th> Puntaje </th> </thead>
                    <tbody>";
                } else {
                    echo "<h2 class='title'> Pool " . $idPool . " </h2>
                    <table class='table'>
                    <thead> <th> Position </th> <th> Name </th> <th> Last Name </th> <th> Score </th> </thead>
                    <tbody>";
                }

                $cont = 0;

                while ($participant = $participants->fetch_assoc()) {
                    $cont++;
                    echo "<tr><td>" . $cont . "</td><td>" . $participant['nombre_competidor'] . "</td><td>" . $participant['apellido_competidor'] . "</td><td>" . $participant['puntaje_final'] . "</td></tr>";
                }

                echo "</tbody> </table>";
            }
        }
    } else {
        if ($lang == "es") {
            die("No todos los participantes fueron puntuados");
        } else {
            die("Not all participants have been scored");
        }
    }
} else {
    if ($lang == "es") {
        die("Seleccione una competencia");
    } else {
        die("Select a competition");
    }
}
