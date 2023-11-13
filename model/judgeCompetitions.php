<?php session_start();

error_reporting(0);

include_once "./Objects/DataBase.php";

if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = 'es';
}

if (isset($_SESSION['event'])) {
    $event = $_SESSION['event'];
} else {
    if ($lang == "es") {
        die("Seleccione un evento");
    } else {
        die("Select an event");
    }
}

if (isset($_SESSION['judgeUser'])) {

    $user = $_SESSION['judgeUser'];

    $connection = mysqli_connect(SERVER, USER, PASS, DB);

    if (!$connection) {
        http_response_code(500);
        echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
    }

    $stmt = "SELECT * FROM competencia JOIN participa ON competencia.id_competencia = participa.id_competencia WHERE usuario_juez = '$user' AND estado = 'activa' AND id_evento = $event ORDER BY competencia.id_competencia DESC";

    $response = mysqli_query($connection, $stmt);

    if (!$response) {
        http_response_code(500);
        echo json_encode(array("error" => "Error: " . $stmt));
    } else {

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
} else {
    http_response_code(400);
    if ($lang == "es") {
        echo json_encode(array("error" => "Ingrese los datos"));
    } else {
        echo json_encode(array("error" => "Enter the participant"));
    }
}
