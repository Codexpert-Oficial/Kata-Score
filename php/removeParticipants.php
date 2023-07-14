<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>

<body>
    <main>
        <?php

        include "./Objects/ParticipantsArray.php";

        $participants = new ParticipantsArray();

        $participants->removeParticipants();

        echo "Participantes eliminados";
        ?>
    </main>
</body>

</html>