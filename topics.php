<?php
    include('controler.php');
?>
<!DOCTYPE HTML>
<html lang="fr">
    <head>
        <title>Forum | Topics</title>
        <meta charset="UTF-8">
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,600&display=swap" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0/js/all.min.js"></script>
        <link rel="stylesheet" href="assets/css/main.css">
        <script src="assets/js/app.js"></script>
    </head>
    <body>
        <?php include('assets/inc/header.php'); ?>
        <div class="container topics">
            <div class="top-container">
                <a href="add-topic.php">
                    <button class="btn btn-dark">Ouvrir un topic</button>
                </a>
            </div>
            <?= $topics ?>
        </div>
        <?php include('assets/inc/footer.php'); ?>
    </body>
</html>