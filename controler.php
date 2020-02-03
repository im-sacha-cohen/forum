<?php

session_start();
include('model.php');

// Par défaut l'utilisateur n'est pas connecté
if (!$_SESSION['connected']) {
    $_SESSION['connected'] = false;
}

// On instancie la classe SQL se trouvant dans model.php
$sql = new SQL();

// INSCRIPTION
    // On échape le code HTML qui pourrait être inclut dans les input pour éviter les failles XSS
    $new_first_name = htmlspecialchars($_POST['new_first_name']);
    $new_second_name = htmlspecialchars($_POST['new_second_name']);
    $username = htmlspecialchars($_POST['username']);
    $new_mail = htmlspecialchars($_POST['new_mail']);
    // On appelle la fonction hashPassword pour hasher nos mots de passe
    $new_password = htmlspecialchars(hashPassword($_POST['new_password']));
    $new_confirm_password = htmlspecialchars(hashPassword($_POST['new_confirm_password']));

    if (isset($_POST['submit_new_account'])) {
        if (!empty($new_first_name) && !empty($new_second_name) && !empty($username) && !empty($new_mail) && !empty($new_password) && !empty($new_confirm_password)) {
            // Si l'adresse mail se termine par viacesi.fr ou cesi.fr
            if (preg_match('/@viacesi.fr$|@cesi.fr$/', $new_mail)) {
                // Si les deux mots de passe correspondent
                if ($new_password == $new_confirm_password) {
                    // Si le mail se termine par cesi.fr on définit comme admin
                    if ((preg_match('/@viacesi.fr$/', $new_mail))) {
                        $admin = 0;
                    } else if ((preg_match('/@cesi.fr$/', $new_mail))) {
                        $admin = 1;
                        $_SESSION['admin'] = true;
                    }

                    // On fait référence à notre class SQL grâce à la variable $sql déclarée plus haut, puis on appelle notre en fonction en passant en paramètre les informations que l'utilisateur a renseigné
                    $sql->addUser($admin, $new_first_name, $new_second_name, $username, $new_mail, $new_confirm_password);
                    $_SESSION['connected'] = true;
                    $_SESSION['first_name'] = $new_first_name;
                    header('Location: index.php');
                } else {
                    echo 'Les deux mots de passe ne correspondent pas';
                }
            } else {
                echo 'L\'inscription est seulement réservée à certains membres...';
            }
        } else {
            echo 'Tous les champs doivent êtes remplis !';
        }
    }
//
// CONNEXION
    $mail = htmlspecialchars($_POST['connect_mail']);
    $password = htmlspecialchars(hashPassword($_POST['connect_password']));

    if (isset($_POST['submit_connection'])) {
        if (!empty($mail) && !empty($password)) {
            $data = $sql->getUserByMail($mail);

            if (count($data) > 0) {
                foreach ($data as $user) {
                    $user_id = $user['id'];
                    $user_mail = $user['mail'];
                    $user_username = $user['username'];
                    $user_password = $user['password'];
                    $user_name = $user['first_name'];
                }

                if ($mail == $user_mail || $mail == $user_username && $password == $user_password) {
                    $_SESSION['connected'] = true;
                    $_SESSION['first_name'] = $user_name;
                    $_SESSION['id'] = $user_id;
                    header('Location: account.php');
                } else {
                    echo 'Votre adresse mail ou votre mot de passe ne correspond pas';
                }
            } else {
                echo 'Votre adresse mail ou votre mot de passe ne correspond pas';
            }
        }
    } 
//
// AJOUTER UN TOPIC
    $topic_title = htmlspecialchars($_POST['topic_title']);
    $topic_message = htmlspecialchars($_POST['topic_message']);

    if (isset($_POST['topic_submit'])) {
        if (!empty($topic_title) && !empty($topic_message)) {
            // Si une image a été entrée
            if ($_FILES['topic_image']['size'] != 0) {
                if ($_FILES['topic_image']['size'] != 0) {
                    $file_extension = pathinfo($_FILES['topic_image']['name']);
                    $extensions = array('jpg', 'jpeg', 'png', 'gif');
                    $move = __DIR__.'/assets/img/' . basename($_FILES['topic_image']['name']);
                    $img_name = htmlspecialchars($_FILES['topic_image']['name']);

                    if (in_array($file_extension['extension'], $extensions)) {
                        if (move_uploaded_file($_FILES['topic_image']['tmp_name'], $move)) {
                            $move = 'assets/img/'. basename($_FILES['topic_image']['name']);
                            $sql->addTopic($_SESSION['id'], $topic_title, $move, $topic_message);
                            header('Location: topics.php');
                        } else {
                            echo 'Une erreur s\'est produite lors de l\'envoi du fichier' . $move;
                        }
                        
                    } else {
                        echo 'L\'extension du fichier n\'est pas autorisée';
                    }
                } else {
                    echo 'Une erreur est survenue au chargement de l\'image';
                }
            } else {
                $move = '';
                $sql->addTopic($_SESSION['id'], $topic_title, $move, $topic_message);
                header('Location: topics.php');
            }
        } else {
            echo 'Le titre et le message du topic doivent êtres renseignés';
        }
    }
//
// RÉCUPÉRER TOUS LES TOPICS
    $data_topics = $sql->getTopics();

    foreach($data_topics as $topic) {
        
        $data_user = $sql->getUserById($topic['id_user']);

        foreach($data_user as $user_by_id) {
            $user_by_id_username = $user_by_id['username'];
        }

        setlocale (LC_TIME, "fr_FR");
        //$actual_date = date("Y-m-d");
        $date_published = date_create($topic['date_published']);
        $date_published = date_format($date_published, 'd/m/Y à H:i');
        //$date_published = strftime("%A %d %B %Y à %H:%M");

        $topics .= '<div class="card text-center topic-container">
                        <div class="card-header topic-top">
                            <span>Par '. $user_by_id_username .'</span>
                        </div>
                        <div class="card-body">
                            <span class="title-topic">'. $topic['title'] .'</span>
                            <a href="topic.php?topic='. $topic['id'] .'" class="btn btn-primary">Voir ce topic</a>
                        </div>
                        <div class="card-footer text-muted">
                            <span>Le '. $date_published .'</span>
                        </div>
                    </div>';
    }
//
// RÉCUPÉRER UN TOPIC PAR L'ID
    if (isset($_GET['topic'])) {
        if (is_numeric($_GET['topic']) && $_GET['topic'] >= 0 && $_GET['topic'] <= 9999) {
            $data_topic_by_id = $sql->getTopicById($_GET['topic']);

            $data_user = $sql->getUserById($topic['id_user']);

            foreach($data_user as $user_by_id) {
                $user_by_id_username = $user_by_id['username'];
            }

            foreach($data_topic_by_id as $topic_by_id) {

                if ($topic_by_id['src'] != null) {
                   $img = '<img src="'. $topic_by_id['src'] .'" alt="image">';
                } else {
                    $img = '';
                }

                $topic_detail .= '<div class="card border-black mb-3">
                                    <div class="card-header">
                                        <h1 class="title-topic">'. $topic_by_id['title'] .'</h1>
                                    </div>
                                    <div class="card-body">
                                        <div class="topic-infos-container">
                                            <h2 class="topic-infos">Par '. $user_by_id_username .', </h2>
                                            <h2 class="topic-infos">le '. $topic_by_id['date_published'] .'</h2>
                                        </div>
                                        <p>'. $topic_by_id['message'] .'</p>
                                        '. $img .'
                                    </div>
                                </div>';
            }
        } else {
            echo 'Topic not valid';
        }
    }
//
// AJOUTER UN COMMENTAIRE
    $id_topic = htmlspecialchars($_POST['id_topic']);
    $comment = htmlspecialchars($_POST['comment']);

    if (isset($_POST['submit_comment'])) {
        if (!empty($comment) && !empty($id_topic) && !empty($_SESSION['id'])) {
            $sql->addComment($id_topic, $_SESSION['id'], $comment);
            header('Location: topic.php?topic='. $id_topic);
        } else {
            echo 'Vous devez remplir la zone de commentaire !';
        }
    }
//
// RÉCUPÉRER TOUS LES COMMENTAIRES CORRESPONDANT AU TOPI
    if (isset($_GET['topic'])) {
        $comments = $sql->getComments($_GET['topic']);

        foreach($comments as $data_comment) {
            $user = $sql->getUserById($data_comment['posted_by']);

            foreach($user as $data_user) {
                $username = $data_user['username'];
            }

            setlocale (LC_TIME, "fr_FR");
            $date_published = date_create($data_comment['posted']);
            $date_published = date_format($date_published, 'd/m/Y à H:i');

            $commentary .= '<div class="card border-light mb-3">
                            <div class="card-body">
                                <h5 class="card-title">'. $username .', le '. $date_published .'</h5>
                                <p class="card-text">
                                    '. $data_comment['comment'] .'
                                </p>
                            </div>
                        </div>';
        }
    }
//
// DÉCONNEXION
    if (isset($_POST['submit_disconnection'])) {
        session_destroy();
        $_SESSION = array();
        header('Location: account.php');
    }
//
function hashPassword($password) {
    $first_salt = '&67FGhyuijkln§è!çà!&!"LLKOiiaualld!4452';
    $first_salt = md5($first_salt);
    $second_salt = 'IokJKL?0987$ù/.,,UUJYyy6"&455';

    $password = hash('sha512', $password . $second_salt);
    $password = $first_salt . $password;
    $password = strtoupper($password);
    return $password;
}