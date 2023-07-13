<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <style>
        .red {
            background-color: red;
            color: white;
        }

        .blue {
            background-color: blue;
            color: white;
        }
    </style>

    <?php

    session_start();

    include "./Objects/ParticipantsArray.php";

    $participants = new ParticipantsArray();

    $participants->sortParticipants();

    $participants->showParticipantsPool();

    ?>

</body>

</html>