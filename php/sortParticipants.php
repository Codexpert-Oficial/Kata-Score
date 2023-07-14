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

        <table class="table">

            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Pool</th>
            </tr>

            <?php

            session_start();

            include "./Objects/ParticipantsArray.php";

            $participants = new ParticipantsArray();

            $participants->sortParticipants();

            $participants->showParticipantsPool();

            ?>

        </table>

    </main>

    <footer>
        <div class="footer__line"></div>
        <p>Desarrollado por Codexpert</p>
    </footer>

</body>

</html>