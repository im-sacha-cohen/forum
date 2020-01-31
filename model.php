<?php

class SQL {
    private function bdd() {
        try {
            return new PDO('mysql:host=localhost;dbname=forum;port=3306;charset=utf8', 'root', 'root');
        } catch (Exception $e) {
            echo 'Caught exception: ' . $e->getMessage();
        }
    }

    public function addUser($admin, $first_name, $second_name, $mail, $password) {
        $bdd = $this->bdd();

        // On prépare la requêtes pour éviter les injections SQL
        $insert = $bdd->prepare('INSERT INTO user (admin, first_name, second_name, mail, password) VALUES (:admin, :first_name, :second_name, :mail, :password)');
        $insert->execute(array(
            ':admin' => $admin,
            ':first_name' => $first_name,
            ':second_name' => $second_name,
            ':mail' => $mail,
            ':password' => $password
        ));
    }

    public function getUserByMail($mail) {
        $bdd = $this->bdd();

        $select = $bdd->query('SELECT * FROM user WHERE mail = "'. $mail . '"');
        $fetch = $select->fetchAll();
        return $fetch;
    }
}