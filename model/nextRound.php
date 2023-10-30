
        <?php

        session_start();

        /* error_reporting(0); */

        define('SERVER', '127.0.0.1');
        define('USER', 'root');
        define('PASS', 'root');
        define('DB', 'kata_score');

        include_once './Objects/Competition.php';
        include_once './Objects/Round.php';
        include_once './Objects/Competes.php';

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

            /* $numRound = $competition->getLastRound();

            $round = new Round($numRound, $competitionID); */

            if (!$competition->passRound()) {
                http_response_code(400);
                echo json_encode(array("error" => "No se pudo completar la accion"));
            } else {
                echo "Accion realizada con exito";
            }

            /* $participants = $round->getParticipants();

            $newRound = new Round($numRound + 1, $competitionID);
            echo $newRound->enterRound();

            $cont = 0;
            while ($participant = $participants->fetch_assoc() && $cont < 4) {
                $competes = new Competes($participant['ci'], $competitionID, $numRound + 1);
                $competes->enterCompetes();
                $cont++;
            } */
        }

        ?>