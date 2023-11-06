<?php
session_start();

/* error_reporting(0); */

define('SERVER', '127.0.0.1');
define('USER', 'root');
define('PASS', 'root');
define('DB', 'kata_score');

if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = 'es';
}

include_once 'Objects/Competition.php';
include_once 'Objects/Round.php';

if (isset($_SESSION["judgeUser"]) && isset($_SESSION["judgeCompetition"])) {
    $competitionID = $_SESSION['judgeCompetition'];
    $user = $_SESSION["judgeUser"];

    $connection = mysqli_connect(SERVER, USER, PASS, DB);

    if (!$connection) {
        echo "Error: " . mysqli_connect_error();
    }

    $stmt = "SELECT * FROM juez JOIN participa ON juez.usuario_juez = participa.usuario_juez WHERE participa.usuario_juez = '$user' AND participa.id_competencia = $competitionID";
    $response = mysqli_query($connection, $stmt);

    if (!$response) {
        echo "Error: " . $stmt;
    } else {
        if ($response->num_rows <= 0) {
            if ($lang = "es") {
                echo "Usuario no registrado";
            } else {
                echo "User not registered";
            }
        } else {

            $judge = $response->fetch_assoc();

            $lastName = $judge['apellido_juez'];
            $name = $judge['nombre_juez'];
            $number = $judge['num_juez'];

            echo "<header>";

            $connection = mysqli_connect(SERVER, USER, PASS, DB);

            if (!$connection) {
                die("Error: " . mysqli_connect_error());
            }

            $stmt = "SELECT * FROM competencia WHERE id_competencia = $competitionID";

            $result = mysqli_query($connection, $stmt);

            $competitionInfo = $result->fetch_assoc();

            $competition = new Competition($competitionInfo['estado'], $competitionInfo['fecha'], $competitionInfo['tipo_equipos'], $competitionInfo['nombre'], $competitionInfo['rango_etario'], $competitionInfo['sexo']);
            $competition->setId($competitionID);
            $numRound = $competition->getLastRound();

            $round = new Round($numRound, $competitionID);

            $participant = $round->getActiveParticipant();

            if (!$participant) {
                if ($lang == "es") {
                    echo "No hay ningun particpante activo";
                } else {
                    echo "There isn't an active participant";
                }
            } else {
                $stmt = "SELECT * FROM realiza JOIN kata ON realiza.id_kata = kata.id_kata WHERE ci = " . $participant['ci'] . " AND num_ronda = $numRound AND id_competencia = $competitionID";

                $response = mysqli_query($connection, $stmt);
                if (!$response) {
                    http_response_code(500);
                    echo json_encode(array("error" => "Error: " . $stmt));
                } else {
                    $kata = $response->fetch_assoc();
                    echo "<section class='header__container'>";
                    if ($lang == "es") {
                        echo "<p class='header__text'> Category: " . $competitionInfo['rango_etario'] . " " . $competitionInfo['sexo'] . "</p>";
                    } else {
                        if ($competitionInfo['sexo'] == "masculino") {
                            echo "<p class='header__text'> Categoría: " . $competitionInfo['rango_etario'] . " Male</p>";
                        } else {
                            echo "<p class='header__text'> Categoría: " . $competitionInfo['rango_etario'] . " Female</p>";
                        }
                    }

                    if (isset($kata['nombre_kata'])) {
                        echo "<p class='header__text'> Kata: " . $kata['nombre_kata'] . "</p>";
                    } else {
                        if ($lang == "es") {
                            echo "<p class='header__text'> Kata: Sin kata</p>";
                        } else {
                            echo "<p class='header__text'> Kata: No kata</p>";
                        }
                    }

                    echo "</section>";
                }
            }
            if ($lang == "es") {
                echo " <section class='header__container'>
                 <p class='header__text'>$lastName, $name Juez N° $number </p> </section>";
                echo "</header>
                <main>
                <article class='jugePanel'>
                <form action='../../../model/scoreParticipant.php' method='post' class='form'>
                <input type='number' min=5 max=10 step=0.1 name='score' class='input' required placeholder='Puntaje'>
                <input type='hidden' value='$user' name='judge'>
                <input type='hidden' value='$competitionID' name='competition'>
                <input type='hidden' value='$numRound' name='round'>
                <input type='submit' value='Enviar' class='button'>
                <a href='' class='button remove__button'>Reset</a>
                </form>
                
                </article>";
            } else {
                echo " <section class='header__container'>
                 <p class='header__text'>$lastName, $name Judge N° $number </p> </section>";

                echo "</header>
                <main>
                <article class='jugePanel'>
                <form action='../../../../model/scoreParticipant.php' method='post' class='form'>
                <input type='number' min=5 max=10 step=0.1 name='score' class='input' required placeholder='Score'>
                <input type='hidden' value='$user' name='judge'>
                <input type='hidden' value='$competitionID' name='competition'>
                <input type='hidden' value='$numRound' name='round'>
                <input type='submit' value='Submit' class='button'>
                <a href='' class='button remove__button'>Reset</a>
                </form>
                
                </article>";
            }

            echo "<article class='msg msg__hidden'>
            <section class='msg__info'>
                <div class='msg__close msg__close-error'>
                    <p class='msg__title'>Error</p>
                    <button class='msg__close__button'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='55' height='55' fill='currentColor' class='bi bi-x'
                            viewBox='0 0 16 16'>
                            <path
                                d='M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z' />
                        </svg>
                    </button>
                </div>
                <div class='msg__content'>
                        <img src='/kata-score/view/imgs/icons/error.svg' class='msg__icon'>
                        <p class='msg__text msg__text-error'>You shouldn't be seeing this message</p>
                    </div>a
                </section>
            </article>
    
            </main>
    
            <footer>
                <div class='footer__line'></div>
                <p>Developed by Codexpert</p>
            </footer>";
        }
    }
} else {
    if ($lang == "es") {
        echo "Ingrese los datos";
    } else {
        echo "Enter the data";
    }
}
