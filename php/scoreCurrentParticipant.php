<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="shortcut icon" href="../imgs/katascore-isologotipo.ico" type="image/x-icon">
</head>

<body>

    <main>
        <?php

        session_start();

        include './Objects/ScoresArray.php';
        include './Objects/ParticipantsArray.php';

        if (isset($_SESSION["currentParticipant"])) {
            $participant = unserialize($_SESSION["currentParticipant"]);
            $scores = new ScoresArray($participant->getCi());
            if (count($scores->getScores()) == 5) {
                $_SESSION["displayParticipant"] = true;
                $_SESSION["displayParticipantScore"] = true;
                $_SESSION["displayClassified"] = false;
                $total = $scores->calcTotal();
                $_SESSION["total"] = $total;
                echo "Acción realizada con éxito";
            } else {
                echo "No todos los jueces han puntuado al participante";
            }
        } else {
            echo "Establezca un participante actual";
        }
        ?>
    </main>

</body>

</html>