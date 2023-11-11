
<?php

session_start();

include_once "./Objects/DataBase.php";

if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = 'es';
}

$connection = mysqli_connect(SERVER, USER, PASS, DB);

if (!$connection) {
    http_response_code(500);
    echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
}

if (isset($_SESSION['event'])) {
    $event = $_SESSION['event'];
} else {
    if ($lang == "es") {
        die("Selecciona un evento");
    } else {
        die("Select an event");
    }
}

$stmt = "SELECT * FROM competencia WHERE estado = 'activa' AND id_evento = $event ORDER BY id_competencia DESC";

$response = mysqli_query($connection, $stmt);

if (!$response) {
    http_response_code(500);
    echo json_encode(array("error" => "Error: " . $stmt));
} else {

    if ($response->num_rows <= 0) {
        if ($lang == "es") {
            echo "<p>No hay competencias activas registradas</p>";
        } else {
            echo "<p>There aren't active competitions registered</p>";
        }
    }

    while ($competition = $response->fetch_assoc()) {
        echo "<section class='competition__element competition__element-active'>
        <button value='" . $competition["id_competencia"] . "' class='competition__info'>
        <div class='competition__info__container'>
            <p class='competition__id'>" . $competition["id_competencia"] . "</p>
            <h2 class='competition__name'>" . $competition["nombre"] . "</h2>
        </div>
        <div class='competition__info__container'>
            <p class='competition__category'>" . $competition["rango_etario"] . " - ";
        if ($lang == "es") {
            echo $competition["sexo"];
        } else {
            if ($competition["sexo"] == "masculino") {
                echo "Male";
            } else {
                echo "Female";
            }
        }
        echo "</p>
            <p class='competition__date'>" . $competition["fecha"] . "</p>
        </div>
        </button>
        <section class='competition__icons__container'>";
        echo "</section>
        </section>";
    }
}

?>