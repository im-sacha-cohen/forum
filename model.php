<?php

// Nouvelle classe SQL
class SQL {
    // Fonction privée bdd qui retournera notre connexion à son appel
    private function bdd() {
        try {
            //return new PDO('mysql:host=localhost;dbname=forum;port=3306;charset=utf8', 'root', 'root');
            return new PDO('mysql:host=localhost;dbname=forum;port=8889;charset=utf8mb4', 'root', 'root');
        } catch (Exception $e) {
            echo 'Caught exception: ' . $e->getMessage();
        }
    }

    public function addUser($admin, $first_name, $second_name, $username, $mail, $password) {
        // On appelle la connexion à notre bdd
        // $this fait référence à sa classe. En l'occurrence SQL.
        // On appelle ensuite dans la class SQL, la fonction bdd()
        $bdd = $this->bdd();

        // On prépare la requête pour éviter les injections SQL
        $insert = $bdd->prepare('INSERT INTO user (admin, first_name, second_name, username, mail, password) VALUES (:admin, :first_name, :second_name, :username, :mail, :password)');
        // On execute notre requête grâce à un tableau associatif
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
        // On va chercher tous les utilisateurs où le mail ou le nom d'utilisateur correspond à ce qui a été renseigné par l'utilisateur
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

    public function getTopicById($id) {
        $bdd = $this->bdd();
        
        $select = $bdd->query('SELECT * FROM topic WHERE id = '. $id);
        $fetch = $select->fetchAll();
        return $fetch;
    }

    public function addComment($id_topic, $posted_by, $src, $comment) {
        $bdd = $this->bdd();
        
        $insert = $bdd->prepare('INSERT INTO comments (id_topic, posted_by, src, comment) VALUES (:id_topic, :posted_by, :src, :comment)');
        $insert->execute(array(
            ':id_topic' => $id_topic,
            ':posted_by' => $posted_by,
            ':src' => $src,
            ':comment' => $comment
        ));
    }

    public function getComments($id_topic) {
        $bdd = $this->bdd();

        $select = $bdd->query('SELECT * FROM comments WHERE id_topic = '. $id_topic);
        $fetch = $select->fetchAll();
        return $fetch;
    }
}