<?php
// On démarre une session
session_start();
// On inclut la page model.php
include('model.php');

// On utilise la libraire PHPMailer pour l'envoi de mail
use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

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
    // Si le bouton a été cliqué
    if (isset($_POST['submit_new_account'])) {
        // Et que tous les champs ont été renseignés
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
                    // On connecte l'utilisateur grâce à la variable de session connected
                    $_SESSION['connected'] = true;
                    // On stocke dans la variable de session first_name le prénom de l'utilisatzur
                    $_SESSION['first_name'] = $new_first_name;
                    // On le redirige vers la page d'accueil
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
            // On appelle notre fonction getUserByMail se trouvant dans model.php pour voir si un utilisateur correspond au mail renseigné
            $data = $sql->getUserByMail($mail);
            // Si c'est le cas
            if (count($data) > 0) {
                // On boucle sur $data (car c'est un tableau qui nous est renvoyé)
                foreach ($data as $user) {
                    // On stocke dans des variables chaque champ récupéré depuis notre bdd
                    $user_id = $user['id'];
                    $user_mail = $user['mail'];
                    $user_username = $user['username'];
                    $user_password = $user['password'];
                    $user_name = $user['first_name'];
                }

                // Si le mail renseigné correspond à celui dans la bdd et que le mot de passe renseigné et hashé correspond à celui dans la bdd
                if ($mail == $user_mail || $mail == $user_username && $password == $user_password) {
                    // On connecte l'utilisateur grâce à la variable de session connected
                    $_SESSION['connected'] = true;
                    // Pareil pour son prénom
                    $_SESSION['first_name'] = $user_name;
                    // Et son id
                    $_SESSION['id'] = $user_id;
                    // Puis on le redirige vers la page account.php
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
                // On stocke l'extesnion de l'image dans $file_extension
                $file_extension = pathinfo($_FILES['topic_image']['name']);
                // On renseigne le tableau des extensions autorisées
                $extensions = array('jpg', 'jpeg', 'JPG', 'JPEG', 'PNG', 'GIF', 'png', 'gif');
                // On stocke dans $move le chemin absolu de notre image
                $move = __DIR__.'/assets/img/' . basename($_FILES['topic_image']['name']);
                // On échappe au cas où le code qui peut-être inséré dans le nom du fichier
                $img_name = htmlspecialchars($_FILES['topic_image']['name']);
                // Si l'extension du fichier matche avec le tableau des extensions autorisées
                if (in_array($file_extension['extension'], $extensions)) {
                    // Si notre image a bien été déplacée du fichier temporaire vers le chemin indiqué dans $move
                    if (move_uploaded_file($_FILES['topic_image']['tmp_name'], $move)) {
                        // On stocke définitivement notre image vers le chemin relatif renseigné dans $move
                        $move = 'assets/img/'. basename($_FILES['topic_image']['name']);
                        // On ajoute le topic
                        $sql->addTopic($_SESSION['id'], $topic_title, $move, $topic_message);
                        // Puis on redirige vers la page des topics
                        header('Location: topics.php');
                    } else {
                        echo 'Une erreur s\'est produite lors de l\'envoi du fichier' . $move;
                    }
                    
                } else {
                    echo 'L\'extension du fichier n\'est pas autorisée';
                }
            } else {
                // Si aucune image n'a été renseignée, alors le chemin est vide
                $move = '';
                // Puis nous ajoutons notre topic en bdd
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
    // On boucle sur le tableau contenu dans $data_topics renvoyé par la requête
    foreach($data_topics as $topic) {
        // Pour chaque topic qu'on récupère, on va chercher le nom d'utilisateur de celui qui l'a écrit
        $data_user = $sql->getUserById($topic['id_user']);
        // Puis on boucle sur le résultat de notre requête
        foreach($data_user as $user_by_id) {
            // On stocke dans la variable ci-dessous le nom d'utilisateur de l'auteur du topic
            $user_by_id_username = $user_by_id['username'];
        }
        // On définit la date locale en fr
        setlocale (LC_TIME, "fr_FR");
        // On créé une date PHP à partir de la date du topic
        $date_published = date_create($topic['date_published']);
        // On formate notre date
        $date_published = date_format($date_published, 'd/m/Y à H:i');

        // On stocke dans $topics notre modèle HTML qu'on va afficher dans la page topics.php
        // -> .= signifie qu'on fait une concaténation. Étant donné qu'on est dans une boucle si on ne concatène pas, notre variable serait écrasée par le résultat suivant à chaque fois. Et donc nous aurions que le dernier résultat.
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
    // Si la variable topic existe dans l'URL
    if (isset($_GET['topic'])) {
        // Si notre variable est un entier et est plus grand ou égal à 0 et est plus petit ou égal à 9999...
        // On fait ça pour protéger un maximum notre appli...
        if (is_numeric($_GET['topic']) && $_GET['topic'] >= 0 && $_GET['topic'] <= 9999) {
            // On requête vers notre bdd le topic séléctionné par l'utilisateur grâce à l'ID du topic
            $data_topic_by_id = $sql->getTopicById($_GET['topic']);
            // On boucle sur notre tableau de résultat
            foreach($data_topic_by_id as $topic_by_id) {
                // On va récupérer le nom d'utilisateur de l'auteur du topic
                $data_user = $sql->getUserById($topic_by_id['id_user']);
                // On boucle sur le résultat
                foreach($data_user as $user_by_id) {
                    // On stocke son nom d'utilisateur dans $user_by_id_username
                    $user_by_id_username = $user_by_id['username'];
                }
                // Si le topic possède une image
                if ($topic_by_id['src'] != null) {
                    // Alors on stocke l'attribut HTML img dans $img
                   $img = '<img src="'. $topic_by_id['src'] .'" alt="image">';
                } else {
                    // Sinon $img est vide
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
    $comment = $_POST['comment'];
    // Par défaut on n'envoie pas de mail
    $send_mail = false;

    if (isset($_POST['submit_comment'])) {
        if (!empty($comment) && !empty($id_topic) && !empty($_SESSION['id'])) {
            // Si une image est renseignée
            if ($_FILES['picture_comment']['size'] != 0) {
                // On stocke l'extension de notre image
                $file_extension = pathinfo($_FILES['picture_comment']['name']);
                // Tableau des extensions autorisées
                $extensions = array('jpg', 'jpeg', 'JPG', 'JPEG', 'PNG', 'GIF', 'png', 'gif');
                $move = __DIR__.'/assets/img/' . basename($_FILES['picture_comment']['name']);
                $img_name = htmlspecialchars($_FILES['picture_comment']['name']);

                if (in_array($file_extension['extension'], $extensions)) {
                    if (move_uploaded_file($_FILES['picture_comment']['tmp_name'], $move)) {
                        $move = 'assets/img/'. basename($_FILES['picture_comment']['name']);
                        $sql->addComment($id_topic, $_SESSION['id'], $move, $comment);
                        //header('Location: topics.php');
                        // Notre va pouvoir être envoyé
                        $send_mail = true;
                    } else {
                        echo 'Une erreur s\'est produite lors de l\'envoi du fichier' . $move;
                    }
                    
                } else {
                    echo 'L\'extension du fichier n\'est pas autorisée';
                }
            } else {
                $move = '';
                $sql->addComment($id_topic, $_SESSION['id'], $move, $comment);
                // Notre va pouvoir être envoyé
                $send_mail = true;
            }
            // Si le commentaire a bien été posté
            if ($send_mail) {
                // On va chercher notre topic
                $topics = $sql->getTopicById($id_topic);
                foreach($topics as $topic) {
                    // Puis on va chercher celui qui a écrit le topic pour pouvoir récupérer son nom, son mail...
                    $users = $sql->getUserById($topic['id_user']);
    
                    foreach($users as $user) {
                        // On stocke dans $client_message le contenu HTML de notre mail
                        $client_message = "<html>
                                    <head>
                                    <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css' integrity='sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh' crossorigin='anonymous'>
                                        <style>
                                            body
                                            {
                                                font-family: 'Montserrat',
                                                font-size: 13px;
                                                font-weight: 100;
                                            }
            
                                            .title { display: flex; flex-direction: column; justify-content: center; align-items: center; margin-bottom: 10px; }
            
                                            h1 {
                                                font-weight: 500;
                                                font-size: 20px;
                                                text-align: center;
                                                margin: 0;
                                            }
    
                                            h3 { font-weight: 500; margin-top: 50px; }
            
                                            .center { 
                                                display: flex;
                                                flex-direction: column;
                                                padding: 10px;
                                                width: 100%;
                                                height: 40px;
                                                align-items: center;
                                                margin: 10px 0; 
                                                font-weight: 300;
                                                text-align: center;
                                            }
    
                                            .btn-group { display: flex; width: 100%; justify-content: space-between; margin-bottom: 50px; }
    
                                            .btn-group a {
                                                display: flex;
                                                align-items: center;
                                                color: white;
                                                text-decoration: none;
                                                padding: 5px 20px;
                                                border-radius: 30px;
                                                justify-content: center;
                                                height: 35px;
                                                width: 99%;
                                                text-align: center;
                                                font-size: 11px;
                                            }
    
                                            img { margin-right: 5px; width: 25px; height: 25px; }
    
                                            .site { margin-top: 40px; text-align: center; }
                                        </style>
                                    </head>
                                    <body>
                                        <h1 style='text-align: center;'>Salut ". $user['first_name'] ." !👋</h1>
                                        <div id='content' style='text-align: center;'>
                                            <div class='center' style='padding 0 15px; text-align: center;'>
                                                <span style='text-align: center;'>Un nouveau commentaire vient d'être posté sur ton topic \"". $topic['title'] ."\"</span>
                                                <h3 style='text-align: center;'>Tu peux y répondre cliquant juste ici 👇</h3>
                                                <div class='btn-group' style='text-align: center; height: 30px;'>
                                                    <a class='btn btn-primary' style='text-align: center; color: #fff; background-color: #007bff; border-color: #007bff; padding: .375rem .75rem; border-radius: 30px; width: 99%;' href='http://localhost:8888/forum/topic.php?topic=". $topic['id'] ."'>
                                                        Répondre au commentaire
                                                    </a>
                                                </div>
                                                <!--<a style='color: black' class='site' href='localhost:8888/forum'</a>-->
                                            </div>
                                        </div>
                                    </body>
                                </html>";
                        
                        // Librairie PHPMailer, doc dispo sur notre ami Google
                        $mail = new PHPMailer;
                        $mail->IsSMTP();
                        // Serveur SMTP
                        $mail->Host = 'smtp.gmail.com';
                        // Authentification SMTP
                        $mail->SMTPAuth = true;
                        // Username de notre serveur SMTP (founir par notre ami Google)
                        $mail->Username = 'mail.forumlatex@gmail.com';
                        // Password de notre serveur SMTP
                        $mail->Password = 'Latex!780'; 
                        // Protocole SSL
                        $mail->SMTPSecure = 'ssl';
                        $mail->Port = 465;
                        // Mail de l'expéditeur ainsi que son nom
                        $mail->setFrom('mail.forumlatex@gmail.com', 'FORUM LaTeX');
                        // On renseigne le mail du receveur
                        $mail->AddAddress($user['mail']);
                        // Notre contenu est en HTML
                        $mail->isHTML(true);
                        // Sujet du mail
                        $mail->Subject = '📝 Nouveau commentaire sur votre topic';
                        // Le corps du mail est contenu dans $client_message
                        $mail->Body = $client_message;
                        // Encodage en UTF-8
                        $mail->CharSet = 'UTF-8';
                        // On envoi le mail
                        $mail->send();
                        // On ferme la connexion SMTP
                        $mail->SmtpClose();
                    }
                    // On redirige l'utilisateur vers le topic qu'il avait séléctionné
                    header('Location: topic.php?topic='. $id_topic);
                }
            }
        } else {
            echo 'Vous devez remplir la zone de commentaire !';
        }
    }
//
// RÉCUPÉRER TOUS LES COMMENTAIRES CORRESPONDANT AU TOPIC
    // Si la variable topic existe dans l'URL
    if (isset($_GET['topic'])) {
        // On va récupérer tous les commentaires associés à notre topic
        $comments = $sql->getComments($_GET['topic']);
        // On boucle ssur notre résultat
        foreach($comments as $data_comment) {
            // On va chercher le nom d'utilisateur de ceux qui ont laissé un commentaire
            $user = $sql->getUserById($data_comment['posted_by']);

            foreach($user as $data_user) {
                $username = $data_user['username'];
            }

            setlocale (LC_TIME, "fr_FR");
            $date_published = date_create($data_comment['posted']);
            $date_published = date_format($date_published, 'd/m/Y à H:i');

            if ($data_comment['src'] != null) {
                $img = '<img class="comment-picture" src="'. $data_comment['src'] .'" alt="image">';
            } else {
                $img = '';
            }

            $commentary .= '<div class="card border-light mb-3">
                            <div class="card-body">
                                <h5 class="card-title">'. $username .', le '. $date_published .'</h5>
                                <p class="card-text">
                                    '. $data_comment['comment'] .'<br/>
                                    '. $img .'
                                </p>
                            </div>
                        </div>';
        }
    }
//
// DÉCONNEXION
    // Si l'utilisateur a cliqué sur le bouton se déconnecter
    if (isset($_POST['submit_disconnection'])) {
        // On détruit la session
        session_destroy();
        // Au cas où, on définit $_SESSION comment tableau vide
        $_SESSION = array();
        // Puis on redirige l'utilisateur vers la pgae accoutn
        header('Location: account.php');
    }
//
// Fonction pour hasher les mots de passe. On n'en a pas forcément besoin, on pourrait directement passer par hash(algorithmeDeHashage, notreMDP) mais pour plus de sécurité on va ajouter quelques couches à notre hashage
function hashPassword($password) {
    // On définit le sel du début de mot de passe. Le sel permet d'ajouter des caractères en plus du mdp de l'utilisateur.
    // Dans cette fonction on ajoute un sel au début et à la fin du mdp
    $first_salt = '&67FGhyuijkln§è!çà!&!"LLKOiiaualld!4452';
    // On hashe le premier sel avec l'algo md5
    $first_salt = md5($first_salt);
    // On définit le second sel
    $second_salt = 'IokJKL?0987$ù/.,,UUJYyy6"&455';

    // On hashe notre mot de passe ainsi que notre deuxième sel grâce à l'algo sha512
    $password = hash('sha512', $password . $second_salt);
    // On concatène ensuite notre premier sel avec notre mdp hashé en sha512
    $password = $first_salt . $password;
    // On met notre mdp en majuscule
    $password = strtoupper($password);
    // On retourne notre mdp hashé et salé
    return $password;

    // On n'est pas obligé de faire tout ça mais pour la sécurité c'est quand même mieux. 
    // On peut encore faire preuve d'imagination pour encore plus sécuriser nos mots de passe...
}