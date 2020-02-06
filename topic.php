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
        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,500,600&display=swap" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0/js/all.min.js"></script>
        <link rel="stylesheet" href="assets/css/main.css">
        <script src="assets/js/app.js"></script>
    </head>
    <body>
        <?php include('assets/inc/header.php'); ?>
        <div class="container topic-detail">
            <?= $topic_detail ?>
            <?= $commentary ?>
            <?php
                if ($_SESSION['connected']) {
                    ?>
                        <button class="btn btn-primary btn-leave-comment">Laisser un commentaire</button>
                        <form class="form form-add-comment hidden" action="controler.php" method="post" enctype="multipart/form-data">
                            <h3>Votre commentaire</h3>
                            <input type="file" class="form-control" name="picture_comment">
                            <?php include('emoji-bar.php'); ?>
                            <textarea name="comment" class="form-control text-zone" placeholder="Votre commentaire" required></textarea>
                            <input type="hidden" name="id_topic" value="<?= $_GET['topic']; ?>">
                            <input type="submit" class="btn btn-success" name="submit_comment" value="Commenter">
                            <button class="btn btn-light btn-cancel">Annuler</button>
                        </form>
                    <?php
                } else {
                    ?>
                        <h5>Vous devez vous connecter pour laisser un commentaire à ce topic</h5>
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