<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir juez</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="shortcut icon" href="../imgs/katascore-isologotipo.ico" type="image/x-icon">
</head>

<body>

    <main>
        <form class="form" action="../../model/addJudge.php" method="post">
            <input type="text" placeholder="Usuario" name='user' class="input" required>
            <input type="number" min='1' max='5' placeholder="Numero" name="number" class="input" required>
            <?php
            session_start();

            error_reporting(0);

            if (isset($_SESSION['competition'])) {
                echo "<input type='submit' value='Añadir' class='button'>";
            } else {
                echo "Seleccione una competencia";
            }

            ?>

        </form>

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

    <script src="../../controller/messages.js"></script>
    <script src="../../controller/verifyData.js"></script>
    <script src="../../controller/formFetch.js"></script>

</body>

</html>