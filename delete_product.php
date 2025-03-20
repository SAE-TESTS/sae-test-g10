<?php
function dbConnect(){
    return new PDO('mysql:host=localhost;dbname=inf2pj_02', 'inf2pj02', 'ahV4saerae', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
}

$bdd = dbConnect();

// ✅ Vérifier que la requête est bien en POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("❌ Erreur : La requête doit être en POST.");
}

// ✅ Vérifier si l'ID du produit est bien reçu (problème ici résolu)
if (!isset($_POST["Id_Produit"]) || empty($_POST["Id_Produit"])) {
    die("⚠ Aucune ID de produit envoyée.");
}

// ✅ Sécurisation du paramètre
$Id_Produit = htmlspecialchars($_POST["Id_Produit"]);

// ✅ Vérifier si le produit existe avant suppression
$verifProduit = $bdd->prepare('SELECT Id_Produit FROM PRODUIT WHERE Id_Produit = :Id_Produit');
$verifProduit->execute(['Id_Produit' => $Id_Produit]);
if (!$verifProduit->fetch()) {
    die("🚨 Erreur : Produit introuvable.");
}

// ✅ Suppression des enregistrements liés (ex: CONTENU)
$delContenu = $bdd->prepare('DELETE FROM CONTENU WHERE Id_Produit = :Id_Produit');
$delContenu->execute(['Id_Produit' => $Id_Produit]);

// ✅ Suppression du produit
$delProduct = $bdd->prepare('DELETE FROM PRODUIT WHERE Id_Produit = :Id_Produit');
$delProduct->execute(['Id_Produit' => $Id_Produit]);

// ✅ Suppression de l'image associée au produit
$imgPath = __DIR__ . "/img_produit/" . $Id_Produit . ".png";
if (file_exists($imgPath)) {
    unlink($imgPath);
}

// ✅ Redirection après suppression
header('Location: produits.php');
exit();