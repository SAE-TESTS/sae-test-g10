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

// Récupération des infos du produit
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

// Vérification et affichage de l’image actuelle
$imgPath = "img_produit/$Id_Produit_Update.png";
$defaultImg = "img_produit/default.png";
$displayImg = file_exists($imgPath) ? $imgPath : $defaultImg;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Modifier Produit</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style_modification.css?v=<?php echo time(); ?>">
</head>
<body>
<div class="container">
    <div class="form-container">
        <h2>Modifier Produit</h2>
        <form action="modify_product.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="IdProductAModifier" value="<?php echo $Id_Produit_Update; ?>">

            <div class="form-row">
                <label>Nom :</label>
                <input type="text" name="nomProduit" value="<?php echo $Nom_Produit; ?>" required>
            </div>

            <div class="form-row">
                <label>Catégorie :</label>
                <select name="categorie">
                    <option value="1" <?php if ($Id_Type_Produit == 1) echo "selected"; ?>>Fruit</option>
                    <option value="2" <?php if ($Id_Type_Produit == 2) echo "selected"; ?>>Légume</option>
                    <option value="3" <?php if ($Id_Type_Produit == 3) echo "selected"; ?>>Graine</option>
                    <option value="4" <?php if ($Id_Type_Produit == 4) echo "selected"; ?>>Viande</option>
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

            <!-- Zone de modification de l'image -->
            <div class="form-row">
                <label>Image actuelle :</label>
                <img id="imgPreview" src="<?php echo $displayImg; ?>" alt="Image du produit" width="150px">
            </div>

            <div class="form-row">
                <label>Changer l’image :</label>
                <input type="file" name="image" id="imageUpload" accept=".png" onchange="previewImage(event)">
            </div>

            <div class="button-container">
                <button type="submit">Confirmer</button>
                <a href="produits.php">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            document.getElementById("imgPreview").src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

</body>
</html>