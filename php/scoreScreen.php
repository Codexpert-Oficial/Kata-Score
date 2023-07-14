<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanteador</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/scoreScreen.css">
</head>

<body>
    <?php

    session_start();

    include "./Objects/ParticipantsArray.php";

    if (isset($_SESSION["displayParticipant"])) {

        if ($_SESSION["displayParticipant"]) {
            $participant = unserialize($_SESSION["currentParticipant"]);

            echo "<header> <section class='header__container'>";
            echo "<p> Categoria: " . $participant->getAgeRange() . " - " . $participant->getGender() . "</p>";
            if (isset($_SESSION['round'])) {
                echo "<p> Ronda " . $_SESSION['round'] . "</p>";
            } else {
                echo "<p> Ronda 1</p>";
                $_SESSION['round'] = 1;
            }
            echo "<p> Pool " . $participant->getPool() . "</p>";
            echo "</section></header>";
            echo "<main> <article class='scoreScreen'>";
            if ($participant->getPool() % 2 == 0) {
                $class = "blueBox";
            } else {
                $class = "redBox";
            }
            echo "<div class='" . $class . "'>";

            if (isset($_SESSION["displayParticipantScore"])) {
                if ($_SESSION["displayParticipantScore"]) {
                    echo "<p>" . $_SESSION["total"] . "</p>";
                }
            }

            echo "</div> <div class='scoreScreen__content'>";

            echo "<p>" . $participant->getLastName() . ", " . $participant->getName() . "</p>";
            echo "<p>" . $participant->getKataName() . "</p> </div>";
        }
    }

    if (isset($_SESSION["displayClassified"])) {

        if ($_SESSION["displayClassified"]) {

            $participants = new ParticipantsArray();
            $pool = $_SESSION["poolDisplay"];

            echo "<main> <article class='classified'>";

            echo "<h1 class='title'> Clasificados </h1>";

            echo "<table class='table'>";

            echo "<tr><th>Posicion</th><th>Nombre</th><th>Apellido</th><th>Puntaje</th></tr>";

            $participants->classifiedParticipants($pool);
            echo "</table>";
        }
    }
    ?>
    </article>
    </main>

</body>

</html>