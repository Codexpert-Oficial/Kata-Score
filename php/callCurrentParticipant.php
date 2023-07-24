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

        if (isset($_SESSION["currentParticipant"])) {
            $_SESSION["displayParticipant"] = true;
            $_SESSION["displayParticipantScore"] = false;
            $_SESSION["displayClassified"] = false;
            echo "Acción realizada con éxito";
        } else {
            echo "Establezca un participante actual";
        }
        ?>
    </main>

</body>

</html>