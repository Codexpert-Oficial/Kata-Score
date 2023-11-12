<?php
session_start();

error_reporting(0);

include_once "./Objects/DataBase.php";

if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = 'es';
}

include_once 'Objects/Competition.php';
include_once 'Objects/Round.php';

if (isset($_SESSION["competition"])) {
    $competitionID = $_SESSION['competition'];

    $connection = mysqli_connect(SERVER, USER, PASS, DB);

    if (!$connection) {
        echo "Error: " . mysqli_connect_error();
    }

    $stmt = "SELECT * FROM competencia WHERE id_competencia = $competitionID";

    $result = mysqli_query($connection, $stmt);

    $competitionInfo = $result->fetch_assoc();

    $competition = new Competition($competitionInfo['estado'], $competitionInfo['fecha'], $competitionInfo['nombre'], $competitionInfo['rango_etario'], $competitionInfo['sexo'], $competitionInfo['id_evento']);
    $competition->setId($competitionID);

    $numRound = $competition->getLastRound();

    if ($lang == "es") {
        echo '<main>

        <article class="controlPanel">

            <h1 class="title">Panel de Control</h1>

            <div class="controlPanel__line"></div>

            <section class="controlPanel__container">

                <h2 class="controlPanel__container__title">Controles de participantes</h2>';

        if ($numRound > 1) {
            echo '<div class="button__container"><div class="button-disabled">Ingresar Participantes</div>';
            echo "<span class='button-disabled__message'>Esta funcion no esta disponible en esta ronda</span></div>";
        } else {
            echo '<a href="enterParticipantMenu.html" class="button">Ingresar Participantes</a>';
        }

        echo '<a href="enterSchool.html" class="button">Ingresar Escuela</a>
        <a href="kataAssign.html" class="button">Asignar kata a participante</a>
                <a href="listParticipants.html" class="button">Listar Participantes</a>
                <a href="currentParticipantMenu.html" class="button">Cambiar participante
                    actual</a>';
        if ($numRound > 1) {
            echo '<div class="button__container"><div class="button-disabled">Sortear Participantes</div>';
            echo "<span class='button-disabled__message'>Esta funcion no esta disponible en esta ronda</span></div>";
        } else {
            echo '<a href="sortParticipants.html" class="button">Sortear Participantes</a>';
        }
        echo '<a href="removeParticipantsByCiMenu.html" class="button remove__button">Quitar
                    Participante</a>

            </section>

            <section class="controlPanel__container">

                <h2 class="controlPanel__container__title">Controles del tanteador</h2>

                <a href="" class="button scoreScreenControl" data-option="mostrando_competencia">Mostrar competencia</a>
                <a href="" class="button scoreScreenControl" data-option="mostrando_participante">Llamar a
                    participante
                    actual</a>
                <a href="" class="button scoreScreenControl" data-option="mostrando_puntaje">Mostrar
                    puntaje
                    de
                    participante actual</a>
                <a href="" class="button nextRound">Pasar a siguiente ronda</a>
                <a href="savePositions.html" class="button">Guardar puestos</a>
                <a href="classifiedParticipantsMenu.html" class="button">Mostrar clasificados</a>

            </section>

            <section class="controlPanel__container">
                <h2 class="controlPanel__container__title">Controles de jueces</h2>
                <a href="enterJudge.html" class="button">Registrar juez</a>';
        if ($numRound > 1) {
            echo "<div class='button__container'><div class='button-disabled'>Añadir juez a la competencia</div>
                            <span class='button-disabled__message'>Esta funcion no esta disponible en esta ronda</span></div>";
        } else {
            echo '<a href="addJudgeMenu.html" class="button">Añadir juez a la competencia</a>';
        }

        if ($numRound > 1) {
            echo "<div class='button__container'><div class='button-disabled'>Quitar Juez</div>
                    <span class='button-disabled__message'>Esta funcion no esta disponible en esta ronda</span></div>";
        } else {
            echo '<a href="removeJudge.html" class="button remove__button">Quitar juez</a>';
        }

        echo '</section>

        </article>

        <article class="msg msg__hidden">
            <section class="msg__info">
                <div class="msg__close msg__close-error">
                    <p class="msg__title">Error</p>
                    <button class="msg__close__button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="55" height="55" fill="currentColor"
                            class="bi bi-x" viewBox="0 0 16 16">
                            <path
                                d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                        </svg>
                    </button>
                </div>
                <div class="msg__content">
                    <img src="../../imgs/icons/error.svg" class="msg__icon">
                    <p class="msg__text msg__text-error">No deberias de estar viendo este mensaje</p>
                </div>
            </section>
        </article>

    </main>

    <footer>
        <div class="footer__line"></div>
        <p>Desarrollado por Codexpert</p>
    </footer>';
    } else {
        echo "<main>

        <article class='controlPanel'>

            <h1 class='title'>Control Panel</h1>

            <div class='controlPanel__line'></div>

            <section class='controlPanel__container'>

                <h2 class='controlPanel__container__title'>Participants controls</h2>";
        if ($numRound > 1) {
            echo "<div class='button__container'><div class='button-disabled'>Enter Participants</div>
            <span class='button-disabled__message'>This function isn't available in this round</span></div>";
        } else {
            echo "<a href='enterParticipantMenu.html' class='button'>Enter Participants</a>";
        }

        echo "<a href='enterSchool.html' class='button'>Enter School</a>
        <a href='kataAssign.html' class='button'>Assign kata to a participant</a>
                <a href='listParticipants.html' class='button'>List Participants</a>
                <a href='currentParticipantMenu.html' class='button'>Change current participant</a>";
        if ($numRound > 1) {
            echo "<div class='button__container'><div class='button-disabled'>Sort Participants</div>
            <span class='button-disabled__message'>This function isn't available in this round</span></div>";
        } else {
            echo "<a href='sortParticipants.html' class='button'>Sort Participants</a>";
        }
        echo "<a href='removeParticipantsByCiMenu.html' class='button remove__button'>Remove Participant</a>

            </section>

            <section class='controlPanel__container'>

                <h2 class='controlPanel__container__title'>Score screen controls</h2>

                <a href='' class='button scoreScreenControl' data-option='mostrando_competencia'>Show competition</a>
                <a href='' class='button scoreScreenControl' data-option='mostrando_participante'>Call
                    current
                    participant</a>
                    <a href='' class='button scoreScreenControl' data-option='mostrando_puntaje'>Show current participant score</a>
                <a href='' class='button nextRound'>Pass to the next round</a>
                <a href='savePositions.html' class='button'>Save positions</a>
                <a href='classifiedParticipantsMenu.html' class='button'>Show classifieds</a>

            </section>

            <section class='controlPanel__container'>
                <h2 class='controlPanel__container__title'>Judges controls</h2>
                <a href='enterJudge.html' class='button'>Register judge</a>";
        if ($numRound > 1) {
            echo "<div class='button__container'><div class='button-disabled'>Add judge to the competition</div>
            <span class='button-disabled__message'>This function isn't available in this round</span></div>";
        } else {
            echo "<a href='addJudgeMenu.html' class='button'>Add judge to the competition</a>";
        }

        if ($numRound > 1) {
            echo "<div class='button__container'><div class='button-disabled'>Remove judge</div>
            <span class='button-disabled__message'>This function isn't available in this round</span></div>";
        } else {
            echo "<a href='removeJudge.html' class='button remove__button'>Remove judge</a>";
        }

        echo  "</section>

        </article>

        <article class='msg msg__hidden'>
            <section class='msg__info'>
                <div class='msg__close msg__close-error'>
                    <p class='msg__title'>Error</p>
                    <button class='msg__close__button'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='55' height='55' fill='currentColor'
                            class='bi bi-x' viewBox='0 0 16 16'>
                            <path
                                d='M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z' />
                        </svg>
                    </button>
                </div>
                <div class='msg__content'>
                    <img src='../../../imgs/icons/error.svg' class='msg__icon'>
                    <p class='msg__text msg__text-error'>No deberias de estar viendo este mensaje</p>
                </div>
            </section>
        </article>

    </main>

    <footer>
        <div class='footer__line'></div>
        <p>Developed by Codexpert</p>
    </footer>";
    }
} else {
    if ($lang == "es") {
        echo "Seleccione una competencia";
    } else {
        echo "Select a competition";
    }
}
