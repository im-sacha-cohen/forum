<?php

class SQL {
    private function bdd() {
        try {
            //return new PDO('mysql:host=localhost;dbname=forum;port=3306;charset=utf8', 'root', 'root');
            return new PDO('mysql:host=localhost;dbname=forum;port=8888;charset=utf8', 'root', 'root');
        } catch (Exception $e) {
            echo 'Caught exception: ' . $e->getMessage();
        }
    }

    public function addUser($admin, $first_name, $second_name, $username, $mail, $password) {
        $bdd = $this->bdd();

        // On prépare la requêtes pour éviter les injections SQL
        $insert = $bdd->prepare('INSERT INTO user (admin, first_name, second_name, username, mail, password) VALUES (:admin, :first_name, :second_name, :username, :mail, :password)');
        $insert->execute(array(
            ':admin' => $admin,
            ':first_name' => $first_name,
            ':second_name' => $second_name,
            ':username' => $username,
            ':mail' => $mail,
            ':password' => $password
        ));
    }

    public function getUserByMail($mail) {
        $bdd = $this->bdd();
        
        $select = $bdd->query('SELECT * FROM user WHERE mail = "'. $mail . '" OR username = "'. $mail .'"');
        $fetch = $select->fetchAll();
        return $fetch;
    }

    public function getUserById($id) {
        $bdd = $this->bdd();

        $select = $bdd->query('SELECT * FROM user WHERE id = '. $id);
        $fetch = $select->fetchAll();
        return $fetch;
    }

    public function addTopic($id_user, $title, $src, $message) {
        $bdd = $this->bdd();

        $insert = $bdd->prepare('INSERT INTO topic (id_user, title, src, message, date_published) VALUES (:id_user, :title, :src, :message, NOW())');
        $insert->execute(array(
            ':id_user' => $id_user,
            ':title' => $title,
            ':src' => $src,
            ':message' => $message
        ));
    }

    public function getTopics() {
        $bdd = $this->bdd();
        
        $select = $bdd->query('SELECT * FROM topic ORDER BY date_published DESC');
        $fetch = $select->fetchAll();
        return $fetch;
    }
}