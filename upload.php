<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si un fichier a été envoyé
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        session_start();
        
        // Connexion à la base de données
        $utilisateur = "inf2pj02";
        $serveur = "localhost";
        $motdepasse = "ahV4saerae";
        $basededonnees = "inf2pj_02";
        $bdd = new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);

        // Récupérer l'email de l'utilisateur
        $mailUti = isset($_SESSION["Mail_Uti"]) ? $_SESSION["Mail_Uti"] : $_SESSION["Mail_Temp"];

        // Récupérer l'ID du producteur
        $requete = 'SELECT PRODUCTEUR.Id_Prod FROM PRODUCTEUR JOIN UTILISATEUR ON PRODUCTEUR.Id_Uti = UTILISATEUR.Id_Uti WHERE UTILISATEUR.Mail_Uti = :mail';
        $queryIdProd = $bdd->prepare($requete);
        $queryIdProd->bindParam(':mail', $mailUti, PDO::PARAM_STR);
        $queryIdProd->execute();
        $returnqueryIdProd = $queryIdProd->fetch(PDO::FETCH_ASSOC);
        
        if (!$returnqueryIdProd) {
            die("Erreur : Impossible de récupérer l'ID du producteur.");
        }

        $Id_Prod = $returnqueryIdProd["Id_Prod"];

        // Définir le dossier de destination
        $targetDir = __DIR__ . "/img_producteur/";

        // Vérifier si le dossier existe, sinon le créer
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Vérifier le type de fichier
        $extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($extension, $allowedExtensions)) {
            die("Format de fichier non autorisé. Formats acceptés : JPG, JPEG, PNG, GIF.");
        }

        // Définir le nom du fichier
        $newFileName = $Id_Prod . '.' . $extension;
        $targetPath = $targetDir . $newFileName;

        // Supprimer l'ancienne image si elle existe
        if (file_exists($targetPath)) {
            unlink($targetPath);
        }

        // Déplacer le fichier téléchargé
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
            echo "L'image a été téléchargée avec succès.";
            header('Location: ./index.php');
            exit();
        } else {
            die("Le déplacement du fichier a échoué.");
        }
    } else {
        die("Erreur lors du téléchargement : " . $_FILES["image"]["error"]);
    }
}
?>
