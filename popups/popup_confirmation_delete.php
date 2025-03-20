<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de suppression</title>
    <link rel="stylesheet" type="text/css" href="css/popup.css">
</head>
<body>

<!-- Popup de confirmation -->
<div id="confirmationPopup" class="popup" style="display:none;">
    <div class="contenuPopup">
        <a href="produits.php">
            <button type="button">X</button>
        </a>
        <p class="titrePopup">Êtes-vous sûr de vouloir supprimer cet élément ?</p>
        <form action="popup_confirmation_delete.php" method="POST">
            <button type="submit" name="confirm_delete" value="yes" class="btn-supprimer">Oui, supprimer</button>
            <a href="produits.php"><button type="button" class="btn-annuler">Non, annuler</button></a>
        </form>
    </div>
</div>

</body>
</html>
