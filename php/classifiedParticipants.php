<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>

<body>

    <main>
        <?php

        session_start();

        include './Objects/ParticipantsArray.php';

        if (isset($_POST["pool"])) {
            $_SESSION["poolDisplay"] = $_POST["pool"];
            $_SESSION["displayClassified"] = true;
            $_SESSION["displayParticipant"] = false;

            $participants = new ParticipantsArray();

            $cont = 0;
            $contPool = 0;

            foreach ($participants->getParticipants() as $participant) {
                $scores = new ScoresArray($participant->getCi());
                if ($participant->getPool() == $_POST["pool"]) {
                    $contPool++;
                    if (count($scores->getScores()) == 5) {
                        $cont++;
                    }
                }
            }

            if ($cont == $contPool) {
                $participants->orderByScore();
                echo "Accion realizada con exito";
            } else {
                echo "No todos los participantes fueron calificados";
            }
        } else {
            echo "Ingrese los datos";
        }
        ?>
    </main>

</body>

</html>