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
    $new_mail = htmlspecialchars($_POST['new_mail']);
    // On appelle la fonction hashPassword pour hasher nos mots de passe
    $new_password = htmlspecialchars(hashPassword($_POST['new_password']));
    $new_confirm_password = htmlspecialchars(hashPassword($_POST['new_confirm_password']));

    if (isset($_POST['submit_new_account'])) {
        if (!empty($new_first_name) && !empty($new_second_name) && !empty($new_mail) && !empty($new_password) && !empty($new_confirm_password)) {
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
                    $sql->addUser($admin, $new_first_name, $new_second_name, $new_mail, $new_confirm_password);
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
                    $user_mail = $user['mail'];
                    $user_password = $user['password'];
                    $user_name = $user['first_name'];
                }

                if ($mail == $user_mail && $password == $user_password) {
                    $_SESSION['connected'] = true;
                    $_SESSION['first_name'] = $user_name;
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
// DÉCONNEXION
    if (isset($_POST['submit_disconnection'])) {
        session_destroy();
        $_SESSION[] = array();
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