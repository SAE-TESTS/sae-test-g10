<!DOCTYPE html>
<html lang="fr">
<head>
<?php
    require "language.php"; 
?>
    <title><?php echo $htmlMarque; ?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style_general.css">
    <link rel="stylesheet" type="text/css" href="css/popup.css">
</head>
<div class="popup">
    <div class="contenuPopup">
    <a href="index.php">
        <button type="button">X</button>
    </a>
        <p class="titrePopup">Photo de profil</p>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <input type="file" name="image" accept=".png" required>
            <p style="font-size: 12px; color: #888;">Attention : L'image doit être en format PNG</p>
            <input type="submit">
        </form>
    </div>
</div>
