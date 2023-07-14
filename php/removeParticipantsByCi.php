<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>

<body>
    <main>
        <?php

        include "./Objects/ParticipantsArray.php";

        $participants = new ParticipantsArray();

        if (isset($_POST["ci"])) {

            $ci = $_POST["ci"];

            echo $participants->removeParticipant($ci);
        } else {
            echo "Ingrese los datos";
        }
        ?>
    </main>
</body>

</html>