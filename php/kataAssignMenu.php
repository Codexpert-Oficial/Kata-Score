<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participantes</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/participantsMenu.css">
    <link rel="shortcut icon" href="../imgs/katascore-isologotipo.ico" type="image/x-icon">
</head>

<body>

    <main>
        <h1 class="title">Participantes</h1>
        <article class="participants__menu">

            <?php

            session_start();

            error_reporting(0);

            include './Objects/Competition.php';

            if (isset($_SESSION['competition'])) {
                $competitionID = $_SESSION['competition'];
            } else {
                die("Ingrese competencia");
            }

            define('SERVER', '127.0.0.1');
            define('USER', 'root');
            define('PASS', 'root');
            define('DB', 'kata_score');

            $connection = mysqli_connect(SERVER, USER, PASS, DB);

            if (!$connection) {
                die("Error de conexion: " . mysqli_connect_error());
            }

            $stmt = "SELECT * FROM competencia WHERE id_competencia = $competitionID";

            $result = mysqli_query($connection, $stmt);

            $competitionInfo = $result->fetch_assoc();

            $competition = new Competition($competitionInfo['estado'], $competitionInfo['fecha'], $competitionInfo['tipo_equipos'], $competitionInfo['nombre'], $competitionInfo['rango_etario'], $competitionInfo['sexo']);
            $competition->setId($competitionID);
            $round = $competition->getLastRound();

            $stmt = "SELECT * FROM competidor JOIN compite ON competidor.ci = compite.ci WHERE compite.id_competencia = $competitionID AND compite.num_ronda = $round";

            $result = mysqli_query($connection, $stmt);

            if (!$result) {
                die("Error en la consulta SQL: " . $stmt);
            }

            while ($participant = $result->fetch_assoc()) {

                $stmt = "SELECT * FROM realiza WHERE ci = " . $participant['ci'] . " AND id_competencia = $competitionID AND num_ronda = $round";

                $response = mysqli_query($connection, $stmt);

                if (!$response) {
                    die("Error en la consulta SQL: " . $stmt);
                }

                $data = $response->fetch_assoc();

                $kata = $data['id_kata'];

                echo "<section class='participant__element'>
                <div class='participant__info'>
                <p class='participant__ci'>" . $participant['ci'] . "</p>
                <p class='participant__name'>" . $participant['nombre_competidor'] . "</p>
                <p class='participant__lastName'>" . $participant['apellido_competidor'] . "</p>
                </div>
                <div class='participant__kata__container'>";
                if ($kata !== null) {
                    echo "<p class='participant__kata'>$kata</p>";
                } else {
                    echo "<p class='participant__kata'>Sin kata</p>";
                }

                echo "<button class='edit__kata' data-participant=" . $participant['ci'] . ">
                    <svg xmlns='http://www.w3.org/2000/svg' width='27' height='27' fill='currentColor' class='bi bi-pencil-square' viewBox='0 0 16 16'>
                        <path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z'/>
                        <path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z'/>
                    </svg>
                </button>
                </div>
                </section>";
            }

            ?>
        </article>

        <article class="assign__kata assign__kata__hidden">

            <form class="form">
                <div class="close__assign__container">
                    <a href="" class="close__assign">
                        <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                        </svg>
                    </a>
                </div>
                <?php
                echo "<input type='hidden' name='round' value=$round>";
                ?>
                <input type="number" min=1 max=102 name='kata' class="input" placeholder="Kata" required>
                <input type="submit" value="Ingresar" class="button">
            </form>

        </article>

        <article class="msg msg__hidden">
            <section class="msg__info">
                <div class="msg__close msg__close-error">
                    <p class="msg__title">Error</p>
                    <button class="msg__close__button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="55" height="55" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                        </svg>
                    </button>
                </div>
                <div class="msg__content">
                    <img src="../imgs/icons/error.svg" class="msg__icon">
                    <p class="msg__text msg__text-error">No deberias de estar viendo este mensaje</p>
                </div>
            </section>
        </article>

    </main>

    <footer>
        <div class="footer__line"></div>
        <p>Desarrollado por Codexpert</p>
    </footer>



    <script src="../js/messages.js"></script>
    <script src="../js/verifyData.js"></script>
    <script src="../js/assignKata.js"></script>
</body>

</html>