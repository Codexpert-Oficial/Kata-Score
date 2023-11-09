<?php

session_start();

error_reporting(0);

include_once "./Objects/DataBase.php";

if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = "es";
}

$connection = mysqli_connect(SERVER, USER, PASS, DB);

if (isset($_SESSION['event'])) {
    $event = $_SESSION['event'];
} else {
    die("Ingrese un evento");
}

$stmt = "SELECT * FROM competencia WHERE id_evento = $event ORDER BY id_competencia DESC";

$result = mysqli_query($connection, $stmt);

if (!$result) {
    die("Error: " . $stmt);
}

while ($competition = $result->fetch_assoc()) {
    if ($competition["estado"] == "activa") {
        echo "<section class='competition__element competition__element-active'>
            <button value='" . $competition["id_competencia"] . "' class='competition__info'>";
    } else {
        echo "<section class='competition__element competition__element-closed'>
                    <button class='competition__info' disabled>";
    }

    echo "<div class='competition__info__container'>  
                    <p class='competition__id'>" . $competition['id_competencia'] . "</p>
                    <h2 class='competition__name'>" . $competition["nombre"] . "</h2>
                </div>
                <div class='competition__info__container'>
                    <p class='competition__category'>" . $competition["rango_etario"];
    if ($lang == "es") {
        echo " - " . $competition["sexo"] . "</p>";
    } else {
        if ($competition["sexo"] == "masculino") {
            echo " - Male</p>";
        } else {
            echo " - Female</p>";
        }
    }
    echo "           <p class='competition__date'>" . $competition["fecha"] . "</p>
                </div>
                </button>
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
