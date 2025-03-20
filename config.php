<?php
function dbConnect() {
    $utilisateur = "inf2pj02"; 
    $serveur = "localhost"; 
    $motdepasse = "ahV4saerae"; 
    $basededonnees = "inf2pj_02"; 

    try {
        $bdd = new PDO("mysql:host=$serveur;dbname=$basededonnees;charset=utf8mb4", $utilisateur, $motdepasse, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $bdd->exec("SET NAMES utf8mb4");
        return $bdd;
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }
}
?>