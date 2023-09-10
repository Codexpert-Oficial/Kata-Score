<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participantes</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="shortcut icon" href="../imgs/katascore-isologotipo.ico" type="image/x-icon">
</head>

<body>

    <main>

        <h1 class="title">Participantes</h1>

        <?php

        session_start();

        include '../../model/Objects/Competition.php';
        include '../../model/Objects/Round.php';

        error_reporting(0);

        define('SERVER', '127.0.0.1');
        define('USER', 'root');
        define('PASS', 'root');
        define('DB', 'kata_score');

        echo "<table class='table'>";
        echo "<tr><th>C.I.</th><th>Nombre</th><th>Apellido</th><th colspan=2>Kata</th><th>Pool</th></tr>";

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
            $competition = new Competition($competitionInfo['estado'], $competitionInfo['fecha'], $competitionInfo['tipo_equipos'], $competitionInfo['nombre'], $competitionInfo['rango_etario'], $competitionInfo['sexo']);
            $competition->setId($competitionID);

            $numRound = $competition->getLastRound();
            $round = new Round($numRound, $competitionID);

            $participants = $round->getParticipantsPools();

            while ($participant = $participants->fetch_assoc()) {

                echo "<tr>
                <td>" . $participant['ci'] . "</td>
                <td>" . $participant['nombre_competidor'] . "</td>
                <td>" . $participant['apellido_competidor'] . "</td>";

                $stmt = "SELECT * FROM realiza JOIN kata on realiza.id_kata = kata.id_kata WHERE ci = " . $participant['ci'] . " AND id_competencia = $competitionID AND num_ronda = $numRound";

                $response = mysqli_query($connection, $stmt);
                if (!$response) {
                    echo "Error al ingresar: " . $stmt;
                } else if ($response->num_rows <= 0) {
                    echo "<td colspan=2>Sin Kata</td>";
                } else {
                    $kata = $response->fetch_assoc();
                    echo "<td>" . $kata['id_kata'] . "</td>
                    <td>" . $kata['nombre_kata'] . "</td>";
                }

                if ($participant['id_pool'] == "") {
                    echo "<td>Sin Pool</td>";
                } else {
                    if ($participant['id_pool'] % 2 == 0) {
                        echo "<td class='blue'>";
                    } else {
                        echo "<td class='red'>";
                    }
                    echo $participant['id_pool'] . "</td>";
                }
                echo "</tr>";
            }
        }

        echo "</table>";

        ?>

    </main>

    <footer>
        <div class="footer__line"></div>
        <p>Desarrollado por Codexpert</p>
    </footer>

</body>

</html>