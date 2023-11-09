<?php

error_reporting(0);

include_once "./Objects/DataBase.php";

if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = "es";
}

$connection = mysqli_connect(SERVER, USER, PASS, DB);

$stmt = "SELECT * FROM evento ORDER BY id_evento DESC";

$result = mysqli_query($connection, $stmt);

if (!$result) {
    die("Error: " . $stmt);
}

while ($event = $result->fetch_assoc()) {
    echo "<section class='competition__element competition__element-active'>
            <button value='" . $event["id_evento"] . "' class='competition__info'>";

    echo "<div class='competition__info__container'>  
                    <p class='competition__id'>" . $event['id_evento'] . "</p>
                    <h2 class='competition__name'>" . $event["nombre"] . "</h2>
                </div>
                </button>
                <section class='competition__icons__container'>
                <button class='competition__element__icon competition__element__icon-can' data-id='" . $event["id_evento"] . "'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor' class='bi bi-trash-fill' viewBox='0 0 16 16'>
                            <path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z' />
                        </svg>
                </button>";
    echo "</section>
    </section>";
}
