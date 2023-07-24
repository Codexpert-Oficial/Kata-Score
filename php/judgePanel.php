<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de juez</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/judgePanel.css">
</head>

<body>





    <?php
    session_start();

    include './Objects/ParticipantsArray.php';

    if (isset($_POST["name"]) && isset($_POST["lastName"]) && isset($_POST["number"])) {

        $name = $_POST["name"];
        $lastName = $_POST["lastName"];
        $number = $_POST["number"];

        if (isset($_SESSION["currentParticipant"])) {
            $participant = unserialize($_SESSION["currentParticipant"]);
            echo "<header> <section class='header__container'>";
            echo "<p class='header__text'> Categoría: " . $participant->getAgeRange() . " " . $participant->getGender() . "</p>";
            echo "<p class='header__text'> Kata: " . $participant->getKataName() . "</p>";
            echo "</section>";
        }

        echo " <section class='header__container'> <p class='header__text'>" . $lastName . ", " . $name . " Juez N° " . $number . "</p> </section>";

        echo "</header>

        <main>
            
        <article class='jugePanel'>";

        echo "<form action='scoreParticipant.php' method='post' class='form'>
        <input type='number' min=5 max=10 step=0.1 name='score' class='input' required placeholder='Puntaje'>
        <input type='hidden' value='" . $number . "' name='number'>
        <input type='submit' value='Enviar' class='button'>
        </form>
        <form action='judgePanel.php' method='post'>
        <input type='hidden' value='" . $name . "' name='name'>
        <input type='hidden' value='" . $lastName . "' name='lastName'>
        <input type='hidden' value='" . $number . "' name='number'>
        <input type='submit' value='Reset' class='button remove__button'>
        </form>";
    } else {
        echo "Ingrese los datos";
    }
    ?>
    </article>

    </main>

    <footer>
        <div class="footer__line"></div>
        <p>Desarrollado por Codexpert</p>
    </footer>
</body>

</html>