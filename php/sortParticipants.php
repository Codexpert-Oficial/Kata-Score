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

        <table class="table">
            <thead>

                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Pool</th>
                </tr>

            </thead>

            <?php

            session_start();

            include './Objects/Round.php';
            include './Objects/Competition.php';

            define('SERVER', '127.0.0.1');
            define('USER', 'root');
            define('PASS', 'root');
            define('DB', 'kata_score');

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

                $round->createPools();

                $participants = $round->getParticipantsPools();
                echo "<tbody>";
                while ($participant = $participants->fetch_assoc()) {
                    echo "<tr>
                    <td>" . $participant['nombre_competidor'] . "</td>
                    <td>" . $participant['apellido_competidor'] . "</td>";
                    if ($participant['id_pool'] % 2 == 0) {
                        echo "<td class='blue'>" . $participant['id_pool'] . "</td></tr>";
                    } else {
                        echo "<td class='red'>" . $participant['id_pool'] . "</td></tr>";
                    }
                }
                echo "</tbody>";
            } else {
                echo ("Seleccione una competencia");
            }


            ?>

        </table>

    </main>

    <footer>
        <div class="footer__line"></div>
        <p>Desarrollado por Codexpert</p>
    </footer>

</body>

</html>