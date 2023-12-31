<?php session_start();

error_reporting(0);

include_once './Objects/Competition.php';

if (isset($_SESSION['competition'])) {
    $competitionID = $_SESSION['competition'];
} else {
    die("Ingrese competencia");
}

include_once "./Objects/DataBase.php";

if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = 'es';
}

$connection = mysqli_connect(SERVER, USER, PASS, DB);

if (!$connection) {
    die("Error: " . mysqli_connect_error());
}

$stmt = "SELECT * FROM competencia WHERE id_competencia = $competitionID";

$result = mysqli_query($connection, $stmt);

$competitionInfo = $result->fetch_assoc();

$competition = new Competition($competitionInfo['estado'], $competitionInfo['fecha'], $competitionInfo['nombre'], $competitionInfo['rango_etario'], $competitionInfo['sexo'], $competitionInfo['id_evento']);
$competition->setId($competitionID);
$round = $competition->getLastRound();

$stmt = "SELECT * FROM competidor JOIN compite ON competidor.ci = compite.ci WHERE compite.id_competencia = $competitionID AND compite.num_ronda = $round";

$result = mysqli_query($connection, $stmt);

if (!$result) {
    die("Error: " . $stmt);
}

while ($participant = $result->fetch_assoc()) {

    $stmt = "SELECT * FROM realiza WHERE ci = " . $participant['ci'] . " AND id_competencia = $competitionID AND num_ronda = $round";

    $response = mysqli_query($connection, $stmt);

    if (!$response) {
        die("Error: " . $stmt);
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
        if ($lang == "es") {
            echo "<p class='participant__kata'>Sin kata</p>";
        } else {
            echo "<p class='participant__kata'>No kata</p>";
        }
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

echo "</article>

            <article class='assign__kata assign__kata__hidden'>
    
                <form class='form'>
                    <div class='close__assign__container'>
                        <a href='' class='close__assign'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='45' height='45' fill='currentColor' class='bi bi-x' viewBox='0 0 16 16'>
                                <path d='M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z' />
                            </svg>
                        </a>
                    </div>
                    <input type='hidden' name='round' value=$round>
                    <input type='number' min=1 max=102 name='kata' class='input' placeholder='Kata' required>";
if ($lang == "es") {
    echo "<input type='submit' value='Ingresar' class='button'>
                </form>";
} else {
    echo "<input type='submit' value='Submit' class='button'>
                </form>";
}
