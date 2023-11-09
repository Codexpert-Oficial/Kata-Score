
        <?php

        session_start();

        include_once './Objects/Competition.php';
        include_once './Objects/Round.php';

        error_reporting(0);

        include_once "./Objects/DataBase.php";

        if (isset($_COOKIE['lang'])) {
            $lang = $_COOKIE['lang'];
        } else {
            $lang = 'es';
        }

        echo "<section class='table__container'>
        <table class='table'>";
        if ($lang == "es") {
            echo "<tr><th>C.I.</th><th>Nombre</th><th>Apellido</th><th colspan=2>Kata</th><th>Pool</th></tr>";
        } else {
            echo "<tr><th>C.I.</th><th>Name</th><th>last Name</th><th colspan=2>Kata</th><th>Pool</th></tr>";
        }


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

            $numRound = $competition->getLastRound();
            $round = new Round($numRound, $competitionID);

            $participants = $round->getParticipantsPools();

            if ($participants->num_rows <= 0) {
                if ($lang == "es") {
                    echo "<tr>
                    <td colspan=6> No hay participantes registrados </td>
                    </tr>";
                } else {
                    echo "<tr>
                    <td colspan=6> No participants registered </td>
                    </tr>";
                }
            } else {

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
                        if ($lang == "es") {
                            echo "<td colspan=2>Sin Kata</td>";
                        } else {
                            echo "<td colspan=2>No Kata</td>";
                        }
                    } else {
                        $kata = $response->fetch_assoc();
                        echo "<td>" . $kata['id_kata'] . "</td>
                        <td>" . $kata['nombre_kata'] . "</td>";
                    }

                    if ($participant['id_pool'] == "") {
                        if ($lang == "es") {
                            echo "<td>Sin Pool</td>";
                        } else {
                            echo "<td>No Pool</td>";
                        }
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
        }

        echo "</table>
        </section>";

        ?>