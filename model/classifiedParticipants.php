
        <?php

        session_start();

        error_reporting(0);

        include_once "./Objects/DataBase.php";

        if (isset($_COOKIE['lang'])) {
            $lang = $_COOKIE['lang'];
        } else {
            $lang = 'es';
        }

        include_once './Objects/Pool.php';
        include_once './Objects/Round.php';
        include_once './Objects/Competition.php';

        if (isset($_POST["pool"]) && isset($_SESSION['competition'])) {


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

            $numRound = $competition->getLastRound();

            $round = new Round($numRound, $competitionID);

            $pool = new Pool($_POST['pool'], null, $competitionID, $numRound);

            if ($pool->allScored()) {
                $round->setShowingPool($_POST['pool']);

                if ($round->setState("mostrando_clasificados")) {
                    if ($lang == "es") {
                        echo "Accion realizada con exito";
                    } else {
                        echo "Action done successfully";
                    }
                }
            } else {
                http_response_code(400);
                if ($lang == "es") {
                    echo json_encode(array("error" => "No todos los participantes fueron calificados"));
                } else {
                    echo json_encode(array("error" => "Not all the participants were rated"));
                }
            }
        } else {
            http_response_code(400);
            if ($lang == "es") {
                echo json_encode(array("error" => "Ingrese los datos"));
            } else {
                echo json_encode(array("error" => "Enter the data"));
            }
        }
        ?>