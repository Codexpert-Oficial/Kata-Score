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

        $currentParticipant = unserialize($_SESSION["currentParticipant"]);
        if (isset($_POST["score"]) && isset($_POST["number"])) {
            $judgeNumber = $_POST["number"];
            $scoreValue = $_POST["score"];
            $participantCi = $currentParticipant->getCi();
            $scores = new ScoresArray($participantCi);

            $score = new Score($scoreValue, $participantCi, $judgeNumber . PHP_EOL);

            echo $scores->enterScore($score);
        } else {
            echo "ingrese los datos";
        }
        ?>
    </main>
</body>

</html>