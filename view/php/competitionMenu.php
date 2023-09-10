<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Competencias</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/competitions.css">
    <link rel="shortcut icon" href="../imgs/katascore-isologotipo.ico" type="image/x-icon">
</head>

<body>

    <main>
        <h1 class="competition__title">Competencias</h1>
        <article class="competition__menu">
            <a href="../html/controlPanel/enterCompetition.html" class="competition__element competition__element-add">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z" />
                </svg>
                Agregar Competencia
            </a>

            <?php

            error_reporting(0);

            define('SERVER', '127.0.0.1');
            define('USER', 'root');
            define('PASS', 'root');
            define('DB', 'kata_score');

            $connection = mysqli_connect(SERVER, USER, PASS, DB);

            $stmt = "SELECT * FROM competencia ORDER BY id_competencia DESC";

            $result = mysqli_query($connection, $stmt);

            if (!$result) {
                die("Error en la consulta SQL: " . $stmt);
            }

            while ($competition = $result->fetch_assoc()) {
                if ($competition["estado"] == "activa") {
                    echo "<section class='competition__element competition__element-active'>
                    <a href='controlPanel.php?competition=" . $competition["id_competencia"] . "' class='competition__info'>";
                } else {
                    echo "<section class='competition__element competition__element-closed'>
                    <a href='' class='competition__info'>";
                }

                echo "<p class='competition__id'>" . $competition['id_competencia'] . "</p>
                <h2 class='competition__name'>" . $competition["nombre"] . "</h2>
                <div class='competition__info__container'>
                    <p class='competition__category'>" . $competition["rango_etario"] . " - " . $competition["sexo"] . "</p>
                    <p class='competition__date'>" . $competition["fecha"] . "</p>
                </div>
                </a>
                <section class='competition__icons__container'>
                <button class='competition__element__icon competition__element__icon-can' data-id='" . $competition["id_competencia"] . "' data-action='delete'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor' class='bi bi-trash-fill' viewBox='0 0 16 16'>
                            <path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z' />
                        </svg>
                </button>";
                if ($competition["estado"] == "activa") {
                    echo "<button class='competition__element__icon competition__element__icon-lock-open' data-id='" . $competition["id_competencia"] . "' data-action='desactivate'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor' class='bi bi-unlock-fill' viewBox='0 0 16 16'>
                        <path d='M11 1a2 2 0 0 0-2 2v4a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h5V3a3 3 0 0 1 6 0v4a.5.5 0 0 1-1 0V3a2 2 0 0 0-2-2z' />
                    </svg>
                    </button>";
                } else {
                    echo "<button class='competition__element__icon competition__element__icon-lock-closed' data-id='" . $competition["id_competencia"] . "' data-action='activate'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor' class='bi bi-lock-fill' viewBox='0 0 16 16'>
                        <path d='M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z' />
                    </svg>
                    </button>";
                }
                echo "</section>
                </section>";
            }

            ?>
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

        <script src="../../controller/messages.js"></script>
        <script src="../../controller/competitionsControls.js"></script>
    </main>

    <footer>
        <div class="footer__line"></div>
        <p>Desarrollado por Codexpert</p>
    </footer>
</body>

</html>