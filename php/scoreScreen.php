<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanteador</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/scoreScreen.css">
    <link rel="shortcut icon" href="../imgs/katascore-isologotipo.ico" type="image/x-icon">
</head>

<body>

    <?php

    session_start();

    define('SERVER', '127.0.0.1');
    define('USER', 'root');
    define('PASS', 'root');
    define('DB', 'kata_score');

    include './Objects/Competition.php';
    include './Objects/Round.php';

    if (isset($_GET['competition'])) {

        $competitionID = $_GET['competition'];

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

        if (isset($_SESSION["displayParticipant"])) {

            if ($_SESSION["displayParticipant"]) {

                $numRound = $competition->getLastRound();
                $round = new Round($numRound, $competitionID);
                $participant = $round->getActiveParticipant();

                $stmt = "SELECT * FROM pertenece WHERE ci = " . $participant['ci'];
                $response = mysqli_query($connection, $stmt);

                echo "<header> <section class='header__container'>";
                echo "<p> Categoría: " . $competition->getAgeRange() . " - " . $competition->getGender() . "</p>
                <p> Ronda: " . $numRound . "</p><p> Pool: ";
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

                    if (isset($_SESSION["displayParticipantScore"])) {
                        if ($_SESSION["displayParticipantScore"]) {
                            echo "<p>" . $round->totalScore($participant['ci']) . "</p>";
                        }
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
            }
        }

        if (isset($_SESSION["displayClassified"])) {

            if ($_SESSION["displayClassified"]) {

                if (isset($_SESSION['poolDisplay'])) {
                    $pool = $_SESSION['poolDisplay'];

                    $stmt = "SELECT nombre_competidor,apellido_competidor,SUM(puntua.puntaje) - MAX(puntua.puntaje) - MIN(puntua.puntaje) AS puntaje_final
                    FROM competidor
                    JOIN puntua ON competidor.ci = puntua.ci
                    JOIN pertenece ON competidor.ci = pertenece.ci
                    WHERE pertenece.id_pool = $pool
                    GROUP BY competidor.ci
                    ORDER BY puntaje_final DESC
                    LIMIT 3";

                    $response = mysqli_query($connection, $stmt);

                    echo "<main> <article class='classified'>";

                    echo "<h1 class='title'> Clasificados </h1>";

                    echo "<table class='table'>";

                    echo "<tr><th>Posición</th><th>Nombre</th><th>Apellido</th><th>Puntaje</th></tr>";
                    $cont = 1;
                    while ($participant = $response->fetch_assoc()) {
                        echo "<tr><td>" . $cont . "</td><td>" . $participant['nombre_competidor'] . "</td><td>" . $participant['apellido_competidor'] . "</td><td>" . $participant['puntaje_final'] . "</td></tr>";
                        $cont++;
                    }
                    echo "</table>";
                }
            }
        }
    }
    ?>
    </article>
    </main>

</body>

</html>