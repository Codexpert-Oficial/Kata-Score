
        <?php

        session_start();

        define('SERVER', '127.0.0.1');
        define('USER', 'root');
        define('PASS', 'root');
        define('DB', 'kata_score');

        include './Objects/Competition.php';
        include './Objects/Round.php';
        include './Objects/Competes.php';

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

            $participants = $round->getParticipants();

            $newRound = new Round($numRound + 1, $competitionID);
            echo $newRound->enterRound();

            while ($participant = $participants->fetch_assoc()) {
                $competes = new Competes($participant['ci'], $competitionID, $numRound + 1);
                $competes->enterCompetes();
            }
        }

        ?>