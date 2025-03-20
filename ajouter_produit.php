<?php
require "language.php"; // Fichier de traduction

// Vérification des variables non définies
if (!isset($htmlUniteDePrix)) $htmlUniteDePrix = "Unité de prix :";
if (!isset($htmlUniteDeQuantite)) $htmlUniteDeQuantite = "Unité de quantité :";
if (!isset($htmlAnnuler)) $htmlAnnuler = "Annuler";
$htmlConfirmerAjoutProduit = "Confirmer l'ajout du produit";
$htmlRetourProduits = "Retour à la liste des produits";

if (!isset($_SESSION["Id_Uti"])) {
    header("Location: index.php"); // Redirection si l'utilisateur n'est pas connecté
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title><?php echo $htmlAjouterProduit; ?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style_general.css">
    <link rel="stylesheet" type="text/css" href="css/ajouter_produit.css">
</head>
<body>

    <div class="container-ajout"> <!-- Centrage global -->
        <h1 class="title"><?php echo $htmlAjouterProduit; ?></h1>

        <div class="form-ajout">
            <form action="insert_products.php" method="post" enctype="multipart/form-data">
                
                <label for="nomProduit"><?php echo $htmlProduitDeuxPoints; ?></label>
                <input type="text" name="nomProduit" placeholder="<?php echo $htmlNomDuProduit; ?>" required>

                <label for="categorie"><?php echo $htmlCategorie; ?></label>
                <select name="categorie">
                    <option value="6"><?php echo $htmlAnimaux; ?></option>
                    <option value="1"><?php echo $htmlFruit; ?></option>
                    <option value="3"><?php echo $htmlGraine; ?></option>
                    <option value="2"><?php echo $htmlLégume; ?></option>
                    <option value="7"><?php echo $htmlPlanche; ?></option>
                    <option value="4"><?php echo $htmlViande; ?></option>
                    <option value="5"><?php echo $htmlVin; ?></option>
                </select>

                <label for="prix"><?php echo $htmlPrix; ?></label>
                <input type="number" min="0" step="0.01" name="prix" required>

                <label for="quantite"><?php echo $htmlStockDeuxPoints; ?></label>
                <input type="number" min="0" step="0.01" name="quantite" required>

                <label for="image"><?php echo $htmlImageDeuxPoints; ?></label>
                <input type="file" name="image" accept=".png">

                <label for="unitPrix"><?php echo $htmlUniteDePrix; ?></label>
                <select name="unitPrix">
                    <option value="1">€/kg</option>
                    <option value="2">€/unité</option>
                    <option value="3">€/litre</option>
                </select>

                <label for="unitQuantite"><?php echo $htmlUniteDeQuantite; ?></label>
                <select name="unitQuantite">
                    <option value="1">kg</option>
                    <option value="2">unité</option>
                    <option value="3">litre</option>
                </select>

                <!-- Boutons confirm & annuler -->
                <div class="button-container">
                    <button type="submit" class="confirm-button"><?php echo $htmlConfirmerAjoutProduit; ?></button>
                    <a href="produits.php" class="cancel-button"><?php echo $htmlRetourProduits; ?></a>
                </div>

            </form>
        </div>
    </div>

</body>
</html>