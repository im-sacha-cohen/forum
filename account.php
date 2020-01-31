<?php
include('controler.php');
?>
<!DOCTYPE HTML>
<html lang="fr">
    <head>
        <title>Forum | Connexion / Inscription</title>
        <meta charset="UTF-8">
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,600&display=swap" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0/js/all.min.js"></script>
        <link rel="stylesheet" href="assets/css/main.css">
        <link rel="stylesheet" href="assets/js/app.js">
    </head>
    <body>
        <?php include('assets/inc/header.php'); ?>
        <div class="container">
            <?php
                if (!$_SESSION['connected']) {
                    ?>
                    <div class="top">
                        <h1>Connectez-vous</h1>
                        <form class="form" action="controler.php" method="post">
                            <input type="email" class="form-control input" name="connect_mail" placeholder="Votre addresse mail" required autofocus>
                            <input type="password" class="form-control input" name="connect_password" placeholder="Votre mot de passe" required>
                            <input type="submit" class="btn btn-primary input" name="submit_connection" value="Se connecter">
                        </form>
                    </div>
                    <div class="separator">
                        <hr>
                        <span>ou</span>
                        <hr>
                    </div>
                    <div class="bottom">
                        <h1>Inscrivez-vous</h1>
                        <form class="form" action="controler.php" method="post">
                            <input type="name" class="form-control input" name="new_first_name" placeholder="Votre prénom" required>
                            <input type="name" class="form-control input" name="new_second_name" placeholder="Votre nom de famille" required>
                            <input type="email" class="form-control input" name="new_mail" placeholder="Votre addresse mail" required>
                            <input type="password" class="form-control input" name="new_password" placeholder="Votre mot de passe" required>
                            <input type="password" class="form-control input" name="new_confirm_password" placeholder="Confirmation de votre mot de passe" required>
                            <input type="submit" class="btn btn-success input" name="submit_new_account" value="S'inscrire">
                        </form>
                    </div>
                    <?php
                } else {
                    ?>
                    <form class="form" action="controler.php" method="post">
                            <input type="submit" class="btn btn-danger input" name="submit_disconnection" value="Se déconnecter">
                        </form>
                    <?php
                }
            ?>
        </div>
    </body>
</html>