<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participantes</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/main.css">
</head>

<body>

    <main>

        <?php

        include "./Objects/ParticipantsArray.php";

        $participants = new ParticipantsArray();

        echo "<table class='table'>";
        echo "<tr><th>C.I.</th><th>Nombre</th><th>Apellido</th><th>Rango Etario</th><th>Sexo</th><th colspan=2>Kata</th><th>Pool</th></tr>";

        $participants->listParticipants();

        echo "</table>";

        ?>

    </main>

    <footer>
        <div class="footer__line"></div>
        <p>Desarrollado por Codexpert</p>
    </footer>

</body>

</html>