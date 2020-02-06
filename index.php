<?php
    include('controler.php');
?>
<!DOCTYPE HTML>
<html lang="fr">
    <head>
        <title>Forum | Accueil</title>
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
            <!-- La ligne en dessous, c'est la même chose que: if ($_SESSION['connected']) {} else {} -->
            <?= $_SESSION['connected'] ? '<h1>Bonjour '. $_SESSION['first_name'] . ' !</h1>' : '<h1>Bienvenue</h1>' ?>
        </div>
        <?php include('assets/inc/footer.php'); ?>
    </body>
</html>