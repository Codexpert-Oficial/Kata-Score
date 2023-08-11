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

        $scores = new ScoresArray(0);
        $scores->removeScores();

        if (isset($_SESSION["round"])) {
            $_SESSION["round"]++;
            echo "Ronda actual: " . $_SESSION["round"];
        } else {
            $_SESSION["round"] = 2;
            echo "Ronda actual: " . $_SESSION["round"];
        }
        ?>
    </main>

</body>

</html>