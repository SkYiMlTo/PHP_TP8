<?php

function connexpdo($base, $user, $password){
    $dsn = 'pgsql:dbname='.$base.';host=127.0.0.1;port=5432';
    $conn = null;
    try {
        $conn = new PDO($dsn, $user, $password);
    } catch (PDOException $e) {
        echo 'Connexion échouée : ' . $e->getMessage();
    }
    return $conn;
}
?>