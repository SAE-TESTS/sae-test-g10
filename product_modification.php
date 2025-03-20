<?php
require "language.php";

// Démarrer la session
if (!isset($_SESSION)) {
    session_start();
}

// Connexion à la base de données
function dbConnect() {
    return new PDO('mysql:host=localhost;dbname=inf2pj_02', 'inf2pj02', 'ahV4saerae', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
}

// Vérifier si l'ID du produit est bien reçu
if (!isset($_POST["modifyIdProduct"]) || empty($_POST["modifyIdProduct"])) {
    header("Location: produits.php");
    exit();
}

$Id_Produit_Update = htmlspecialchars($_POST["modifyIdProduct"]);
$_SESSION["Id_Produit"] = $Id_Produit_Update;

// Connexion et récupération des informations du produit
$bdd = dbConnect();
$queryGetProducts = $bdd->prepare('SELECT * FROM PRODUIT WHERE Id_Produit = :Id_Produit_Update');
$queryGetProducts->execute(['Id_Produit_Update' => $Id_Produit_Update]);
$produit = $queryGetProducts->fetch();

// Vérifier si le produit existe
if (!$produit) {
    header("Location: produits.php");
    exit();
}

// Récupération des données du produit
$Nom_Produit = htmlspecialchars($produit["Nom_Produit"]);
$Id_Type_Produit = $produit["Id_Type_Produit"];
$Qte_Produit = $produit["Qte_Produit"];
$Prix_Produit_Unitaire = $produit["Prix_Produit_Unitaire"];

$types_produit = [
    1 => $htmlFruit, 2 => $htmlLégume, 3 => $htmlGraine, 4 => $htmlViande,
    5 => $htmlVin, 6 => $htmlAnimaux, 7 => $htmlPlanche
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Modifier Produit</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style_modification.css">
</head>
<body>
<div class="container">
    <!-- Formulaire de modification -->
    <div class="form-container">
        <h2>Modifier Produit</h2>
        <form action="modify_product.php" method="post">
            <input type="hidden" name="IdProductAModifier" value="<?php echo $Id_Produit_Update; ?>">

            <div class="form-row">
                <label>Nom :</label>
                <input type="text" name="nomProduit" value="<?php echo $Nom_Produit; ?>" required>
            </div>

            <div class="form-row">
                <label>Catégorie :</label>
                <select name="categorie">
                    <?php foreach ($types_produit as $id => $nom): ?>
                        <option value="<?php echo $id; ?>" <?php if ($id == $Id_Type_Produit) echo "selected"; ?>><?php echo $nom; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <label>Prix :</label>
                <input type="number" name="prix" step="0.01" value="<?php echo $Prix_Produit_Unitaire; ?>" required>
            </div>

            <div class="form-row">
                <label>Stock :</label>
                <input type="number" name="quantite" value="<?php echo $Qte_Produit; ?>" required>
            </div>

            <!-- Boutons -->
            <div class="button-container">
                <button type="submit">Confirmer</button>
                <a href="produits.php">Annuler</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>