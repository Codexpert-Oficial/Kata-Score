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

        $participants = new ParticipantsArray();
        $scores = new ScoresArray(0);

        $participants->removeParticipants();

        $scores->removeScores();

        unset($_SESSION["currentParticipant"]);

        unset($_SESSION["poolDisplay"]);

        $_SESSION["round"] = 1;

        echo "Informacion eliminada con exito";

        ?>

    </main>

</body>

</html>