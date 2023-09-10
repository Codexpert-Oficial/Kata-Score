
        <?php

        session_start();

        include './Objects/Competition.php';
        include './Objects/Round.php';

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

            $participant = $round->getActiveParticipant();

            if ($participant != false) {
                $_SESSION["displayParticipant"] = true;
                $_SESSION["displayParticipantScore"] = false;
                $_SESSION["displayClassified"] = false;
                echo "Acción realizada con éxito";
            }
        } else {
            http_response_code(400);
            echo json_encode(array("error" => "Seleccione una competencia"));
        }
        ?>