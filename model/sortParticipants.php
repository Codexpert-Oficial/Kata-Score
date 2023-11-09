
            <?php

            session_start();

            include_once './Objects/Round.php';
            include_once './Objects/Competition.php';

            include_once "./Objects/DataBase.php";

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
                    echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
                }

                $stmt = "SELECT * FROM competencia WHERE id_competencia = $competitionID";

                $response = mysqli_query($connection, $stmt);

                $competitionInfo = $response->fetch_assoc();

                $competition = new Competition($competitionInfo['estado'], $competitionInfo['fecha'], $competitionInfo['nombre'], $competitionInfo['rango_etario'], $competitionInfo['sexo'], $competitionInfo['id_evento']);
                $competition->setId($competitionID);
                $numRound = $competition->getLastRound();

                $round = new Round($numRound, $competitionID);
                $participants = $round->getParticipants();

                echo "<tbody>";

                if ($participants->num_rows <= 0) {
                    if ($lang == "es") {
                        echo "<tr>
                        <td colspan=3> No hay participantes registrados </td>
                        </tr>";
                    } else {
                        echo "<tr>
                        <td colspan=3> No participants registered </td>
                        </tr>";
                    }
                } else {

                    $round->createPools();

                    $participants = $round->getParticipantsPools();

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
                }


                echo "</tbody>";
            } else {
                if ($lang == "es") {
                    echo "Seleccione una competencia";
                } else {
                    echo "Select a competition";
                }
            }


            ?>