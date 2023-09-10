<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/controlPanel.css">
    <link rel="shortcut icon" href="../imgs/katascore-isologotipo.ico" type="image/x-icon">
</head>

<body>
    <?php
    session_start();
    if (isset($_GET["competition"])) {
        $competition = $_GET['competition'];
        $_SESSION['competition'] = $competition;
    }
    ?>
    <main>

        <article class="controlPanel">

            <h1 class="title">Panel de Control</h1>

            <div class="controlPanel__line"></div>

            <section class="controlPanel__container">

                <h2 class="controlPanel__container__title">Controles de Participantes</h2>

                <a href="../html/controlPanel/enterParticipantMenu.html" class="button">Ingresar Participantes</a>
                <a href="kataAssignMenu.php" class="button">Asignar kata a participante</a>
                <a href="listParticipants.php" class="button">Listar Participantes</a>
                <a href="../html/controlPanel/currentParticipantMenu.html" class="button">Cambiar participante actual</a>
                <a href="sortParticipants.php" class="button">Sortear Participantes</a>
                <a href="../html/controlPanel/removeParticipantsByCiMenu.html" class="button remove__button">Eliminar
                    Participantes por CI</a>

            </section>

            <section class="controlPanel__container">

                <h2 class="controlPanel__container__title">Controles del tanteador</h2>

                <a href="callCurrentParticipant.php" class="button callCurrentParticipant">Llamar a participante
                    actual</a>
                <a href="scoreCurrentParticipant.php" class="button scoreCurrentParticipant">Mostrar puntaje de
                    participante actual</a>
                <a href="nextRound.php" class="button nextRound">Pasar a siguiente ronda</a>
                <a href="../html/controlPanel/classifiedParticipantsMenu.html" class="button">Mostrar clasificados</a>

            </section>

            <section class="controlPanel__container">
                <h2 class="controlPanel__container__title">Controles de jueces</h2>
                <a href="../html/controlPanel/enterJudge.html" class="button">Registrar juez</a>
                <a href='addJudgeMenu.php' class='button'>Añadir juez a la competencia</a>
            </section>

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
    <script src="../js/scoreScreen.js"></script>

</body>

</html>