<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de juez</title>
</head>

<body>

    <?php
    session_start();
    include "./Objects/ParticipantsArray.php";

    if (isset($_POST["name"]) && isset($_POST["lastName"]) && isset($_POST["number"])) {

        $name = $_POST["name"];
        $lastName = $_POST["lastName"];
        $number = $_POST["number"];

        if (isset($_SESSION["currentParticipant"])) {
            $participant = unserialize($_SESSION["currentParticipant"]);
            echo "<p> Categoria: " . $participant->getAgeRange() . " " . $participant->getGender() . "</p>";
            echo "<p> Kata: " . $participant->getKataName() . "</p>";
        }

        echo "<p>" . $lastName . ", " . $name . " Juez N° " . $number . "</p>";

        echo "<form action='scoreParticipant.php' method='post'>
        <input type='number' min=5 max=10 step=0.1 name='score'>
        <input type='hidden' value='" . $number . "' name='number'>
        <input type='submit' value='Enviar'>
        </form>
        <a href=''>Reset</a>";
    } else {
        echo "Ingrese los datos";
    }
    ?>


</body>

</html>