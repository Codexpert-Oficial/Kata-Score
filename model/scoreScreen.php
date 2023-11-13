<?php session_start();

error_reporting(0);

include_once "./Objects/DataBase.php";

if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = 'es';
}

include_once './Objects/Competition.php';
include_once './Objects/Round.php';

if (isset($_SESSION['scoreCompetition'])) {

    $competitionID = $_SESSION['scoreCompetition'];

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

    $state = $round->getState();

    if ($state == "mostrando_participante" || $state == "mostrando_puntaje") {

        $participant = $round->getActiveParticipant();

        $stmt = "SELECT * FROM pertenece WHERE ci = " . $participant['ci'];
        $response = mysqli_query($connection, $stmt);

        echo "<header> <section class='header__container'>";
        if ($lang == "es") {
            echo "<p> Categoría: " . $competition->getAgeRange() . " - " . $competition->getGender() . "</p>
                    <p> Ronda: " . $numRound . "</p><p> Pool: ";
        } else {
            if ($competition->getGender() == "masculino") {
                echo "<p> Category: " . $competition->getAgeRange() . " - Male</p>
                        <p> Round: " . $numRound . "</p><p> Pool: ";
            } else {
                echo "<p> Category: " . $competition->getAgeRange() . " - Female</p>
                        <p> Round: " . $numRound . "</p><p> Pool: ";
            }
        }

        if ($response->num_rows <= 0) {
            echo "Sin Pool </p></section></header><main><article class='scoreScreen'>";
        } else {
            $pool = $response->fetch_assoc();
            echo $pool['id_pool'] . "</p></section></header><main><article class='scoreScreen'>";

            if ($pool['id_pool'] % 2 == 0) {
                echo "<div class='blueBox'>";
            } else {
                echo "<div class='redBox'>";
            }

            if ($state == "mostrando_puntaje") {
                echo "<p>" . $round->totalScore($participant['ci']) . "</p>";
            }

            echo "</div>";
        }

        echo "<div class='scoreScreen__content'>";

        echo "<p>" . $participant['apellido_competidor'] . ", " . $participant['nombre_competidor'] . "</p>";

        $stmt = "SELECT * FROM realiza JOIN kata ON realiza.id_kata = kata.id_kata WHERE ci = " . $participant['ci'] . " AND id_competencia = $competitionID AND num_ronda = $numRound";

        $response = mysqli_query($connection, $stmt);

        if ($response->num_rows <= 0) {
            echo "<p>Sin kata</p> </div>";
        } else {
            $kata = $response->fetch_assoc();
            echo "<p>" . $kata['nombre_kata'] . "</p> </div>";
        }
    } else if ($state == "mostrando_competencia") {

        echo "<main> <article class='scoreScreen__competition'>
            <h1>" . $competition->getName() . "</h1>
            <p class='kata'> KATA </p>";
        if ($lang == "es") {
            echo "<section> <p>" . $competition->getAgeRange() . " años - " . $competition->getGender() . "</p> </section></article> </main>";
        } else {
            if ($competition->getGender() == "masculino") {
                echo "<section> <p>" . $competition->getAgeRange() . " years old - Male</p> </section></article> </main>";
            } else {
                echo "<section> <p>" . $competition->getAgeRange() . " years old - Female</p> </section></article> </main>";
            }
        }
    } else if ($state == "mostrando_clasificados") {


        if (!$competition->isLastRound()) {
            $pool = $round->getShowingPool();

            $stmt = "SELECT nombre_competidor,apellido_competidor,SUM(puntua.puntaje) - MAX(puntua.puntaje) - MIN(puntua.puntaje) + COALESCE(puntaje_extra, 0) AS puntaje_final
                FROM competidor
                JOIN puntua ON competidor.ci = puntua.ci
                JOIN pertenece ON competidor.ci = pertenece.ci
                WHERE pertenece.id_pool = $pool
                AND pertenece.id_competencia = $competitionID
                AND pertenece.num_ronda = $numRound
                GROUP BY competidor.ci
                ORDER BY puntaje_final DESC
                LIMIT 3";

            $response = mysqli_query($connection, $stmt);
        } else {
            $stmt = "SELECT COALESCE(puesto,0) puesto, nombre_competidor, apellido_competidor, SUM(puntaje) - MAX(puntaje) - MIN(puntaje) + COALESCE(puntaje_extra, 0) AS puntaje_final
                FROM compite c JOIN puntua p ON c.id_competencia = p.id_competencia AND c.num_ronda = p.num_ronda AND c.ci = p.ci
                JOIN competidor ON c.ci = competidor.ci
                WHERE c.id_competencia = $competitionID AND c.num_ronda = $numRound GROUP BY c.ci ORDER BY puesto";

            $response = mysqli_query($connection, $stmt);
        }


        echo "<main> <article class='classified'>";

        if ($lang == "es") {
            echo "<h1 class='title'> Clasificados </h1>";
        } else {
            echo "<h1 class='title'> Clasifieds </h1>";
        }

        echo "<section class='table__container'>
                <table class='table'>";

        if ($lang == "es") {
            echo "<tr><th>Posición</th><th>Nombre</th><th>Apellido</th><th>Puntaje</th></tr>";
        } else {
            echo "<tr><th>Position</th><th>Name</th><th>Last name</th><th>Score</th></tr>";
        }

        if (!$competition->isLastRound()) {
            $cont = 1;
            while ($participant = $response->fetch_assoc()) {
                echo "<tr><td>" . $cont . "</td><td>" . $participant['nombre_competidor'] . "</td><td>" . $participant['apellido_competidor'] . "</td><td>" . $participant['puntaje_final'] . "</td></tr>";
                $cont++;
            }
        } else {
            while ($participant = $response->fetch_assoc()) {
                if ($participant['puesto'] != 0) {
                    echo "<tr><td>" . $participant['puesto'] . "</td><td>" . $participant['nombre_competidor'] . "</td><td>" . $participant['apellido_competidor'] . "</td><td>" . $participant['puntaje_final'] . "</td></tr>";
                }
            }
        }


        echo "</table>
                </section>
                </article>
                </main>";
    }
}
