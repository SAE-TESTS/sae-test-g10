<?php
function dbConnect() {
    return new PDO('mysql:host=localhost;dbname=inf2pj_02', 'inf2pj02', 'ahV4saerae', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
}

$bdd = dbConnect();

// Vérifier si le formulaire a été soumis via POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Erreur : Requête invalide.");
}

// Vérifier que toutes les données sont bien reçues
if (!isset($_POST["IdProductAModifier"], $_POST["nomProduit"], $_POST["categorie"], $_POST["prix"], $_POST["quantite"])) {
    die("Erreur : Tous les champs doivent être remplis.");
}

// Sécuriser les entrées utilisateur
$Id_Produit = htmlspecialchars($_POST["IdProductAModifier"]);
$Nom_Produit = htmlspecialchars($_POST["nomProduit"]);
$Categorie = htmlspecialchars($_POST["categorie"]);
$Prix = htmlspecialchars($_POST["prix"]);
$Quantite = htmlspecialchars($_POST["quantite"]);

// Mise à jour du produit
$updateProduit = "UPDATE PRODUIT 
                  SET Nom_Produit = :Nom_Produit, 
                      Id_Type_Produit = :Categorie, 
                      Qte_Produit = :Quantite, 
                      Prix_Produit_Unitaire = :Prix 
                  WHERE Id_Produit = :Id_Produit";

$stmt = $bdd->prepare($updateProduit);
$stmt->execute([
    ':Nom_Produit' => $Nom_Produit,
    ':Categorie' => $Categorie,
    ':Quantite' => $Quantite,
    ':Prix' => $Prix,
    ':Id_Produit' => $Id_Produit
]);

// Gestion de l'upload de l'image
if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
    $targetDir = __DIR__ . "/img_produit/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $newFileName = $Id_Produit . ".png";  
    $targetPath = $targetDir . $newFileName;

    if (file_exists($targetPath)) {
        unlink($targetPath);
    }

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
        echo "Image enregistrée avec succès.";
    } else {
        die("Erreur : Impossible de déplacer le fichier.");
    }
}

header('Location: produits.php');
exit();