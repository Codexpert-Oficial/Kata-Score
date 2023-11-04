
            <?php

            session_start();

            define('SERVER', '127.0.0.1');
            define('USER', 'root');
            define('PASS', 'root');
            define('DB', 'kata_score');

            if (isset($_COOKIE['lang'])) {
                $lang = $_COOKIE['lang'];
            } else {
                $lang = 'es';
            }

            $connection = mysqli_connect(SERVER, USER, PASS, DB);

            if (!$connection) {
                http_response_code(500);
                echo json_encode(array("error" => "Error de conexion: " . mysqli_connect_error()));
            }

            $stmt = "SELECT * FROM competencia WHERE estado = 'activa'";

            $response = mysqli_query($connection, $stmt);

            if (!$response) {
                http_response_code(500);
                echo json_encode(array("error" => "Error: " . $stmt));
            } else {

                while ($competition = $response->fetch_assoc()) {
                    echo "<section class='competition__element competition__element-active'>
                    <button value='" . $competition["id_competencia"] . "' class='competition__info'>
                    <div class='competition__info__container'>
                        <p class='competition__id'>" . $competition["id_competencia"] . "</p>
                        <h2 class='competition__name'>" . $competition["nombre"] . "</h2>
                    </div>
                    <div class='competition__info__container'>
                        <p class='competition__category'>" . $competition["rango_etario"] . " - ";
                    if ($lang == "es") {
                        echo $competition["sexo"];
                    } else {
                        if ($competition["sexo"] == "masculino") {
                            echo "Male";
                        } else {
                            echo "Female";
                        }
                    }
                    echo "</p>
                        <p class='competition__date'>" . $competition["fecha"] . "</p>
                    </div>
                    </button>
                    <section class='competition__icons__container'>";
                    echo "</section>
                    </section>";
                }
            }

            ?>