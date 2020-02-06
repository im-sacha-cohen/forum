<?php
    include('controler.php');
?>
<!DOCTYPE HTML>
<html lang="fr">
    <head>
        <title>Forum | Ouvrir un topic</title>
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
        <div class="container">
            <?php
                if ($_SESSION['connected']) {
                    ?>
                    <h1>Ouvrir un topic</h1>
                    <form class="form form-topic" action="controler.php" method="post" enctype="multipart/form-data">
                        <input type="text" class="form-control input" name="topic_title" placeholder="Titre du topic" required autofocus>
                        <input type="file" class="form-control input" name="topic_image">
                        <?php include('emoji-bar.php'); ?>
                        <textarea class="form-control input text-zone" name="topic_message" placeholder="Votre message" required></textarea>
                        <input type="submit" class="btn btn-primary input" name="topic_submit" value="Poster">
                    </form>
                    <?php
                } else {
                    ?>
                    <h1>Pour ouvrir un topic, vous devez vous connecter</h1>
                    <a href="account.php">
                        <button class="btn btn-dark">Se connecter</button>
                    </a>
                    <?php
                }
            ?>
        </div>
        <?php include('assets/inc/footer.php'); ?>
    </body>
</html>