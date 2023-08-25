<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de juez</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/judgePanel.css">
    <link rel="shortcut icon" href="../imgs/katascore-isologotipo.ico" type="image/x-icon">
</head>

<body>

    <?php
    session_start();

    include './Objects/ParticipantsArray.php';

    if (isset($_SESSION["judgeUser"])) {

        $user = $_SESSION["judgeUser"];

        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            echo "Error de conexion: " . mysqli_connect_error();
        }

        $stmt = "SELECT * FROM juez WHERE usuario_juez = '$user'";
        $response = mysqli_query($connection, $stmt);

        if (!$response) {
            echo "Error al ingresar: " . $stmt;
        } else {
            if ($response->num_rows <= 0) {
                echo "Usuario no registrado";
            } else {

                $judge = $response->fetch_assoc();

                $lastName = $judge['apellido_juez'];
                $name = $judge['nombre_juez'];

                echo "<header>";

                if (isset($_SESSION["currentParticipant"])) {
                    $participant = unserialize($_SESSION["currentParticipant"]);
                    echo "<section class='header__container'>";
                    echo "<p class='header__text'> Categoría: " . $participant->getAgeRange() . " " . $participant->getGender() . "</p>";
                    echo "<p class='header__text'> Kata: " . $participant->getKataName() . "</p>";
                    echo "</section>";
                }

                echo " <section class='header__container'> <p class='header__text'>" . $lastName . ", " . $name . " Juez N° </p> </section>";

                echo "</header>
                <main>
                <article class='jugePanel'>
                <form action='scoreParticipant.php' method='post' class='form'>
                <input type='number' min=5 max=10 step=0.1 name='score' class='input' required placeholder='Puntaje'>
                <input type='hidden' value='' name='number'>
                <input type='submit' value='Enviar' class='button'>
                <a href='' class='button remove__button'>Reset</a>
                </form>
                
                </article>
                </main>";
            }
        }
    } else {
        echo "Ingrese los datos";
    }
    ?>

    <footer>
        <div class="footer__line"></div>
        <p>Desarrollado por Codexpert</p>
    </footer>
</body>

</html>