<!DOCTYPE html>
<html lang="es">

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

        include "./Objects/ParticipantsArray.php";

        $participants = new ParticipantsArray();

        if (isset($_POST["ci"])) {
            $ci = $_POST["ci"];

            $participant =  $participants->getParticipant($ci);
            if (isset($participant)) {
                $_SESSION["currentParticipant"] = serialize($participant);
                echo "Acción realizada con éxito";
            } else {
                echo "Usuario no registrado";
            }
        } else {
            echo "Ingrese los datos";
        }
        ?>

    </main>
</body>

</html>