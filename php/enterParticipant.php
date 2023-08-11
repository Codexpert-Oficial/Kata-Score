<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensaje de confirmación</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="shortcut icon" href="../imgs/katascore-isologotipo.ico" type="image/x-icon">
</head>

<body>

    <main>

        <?php

        include './Objects/ParticipantsArray.php';

        if (isset($_POST["ci"]) && isset($_POST["name"]) && isset($_POST["lastName"]) && isset($_POST["kata"]) && isset($_POST["ageRange"]) && isset($_POST["gender"])) {

            $ci = $_POST["ci"];
            $name = $_POST["name"];
            $lastName = $_POST["lastName"];
            $idKata = $_POST["kata"];
            $ageRange = $_POST["ageRange"];
            $gender = $_POST["gender"];

            $participant = new Participant($ci, $name, $lastName, $ageRange, $gender, $idKata, 0 . PHP_EOL);
            $participants = new ParticipantsArray();
            echo $participants->enterParticipant($participant);
        } else {
            echo "Ingrese los datos";
        }

        ?>
    </main>
</body>

</html>