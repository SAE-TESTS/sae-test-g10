<?php
    require "language.php" ; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title><?php echo $htmlMarque; ?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style_general.css">
    <link rel="stylesheet" type="text/css" href="css/popup.css">
</head>
<body>

<?php
     function dbConnect()
     {
         $utilisateur = "inf2pj02";
         $serveur = "localhost";
         $motdepasse = "ahV4saerae";
         $basededonnees = "inf2pj_02";
 
         $bdd = new PDO("mysql:host=$serveur;dbname=$basededonnees;charset=utf8mb4", $utilisateur, $motdepasse, [
             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
         ]);
 
         $bdd->exec("SET NAMES utf8mb4");
         return $bdd;
     }
      if(!isset($_SESSION)){
        session_start();
    }
      $utilisateur=$_SESSION["Id_Uti"];
      htmlspecialchars($utilisateur);

      $bdd=dbConnect();
      $queryIdProd = $bdd->prepare(('SELECT Id_Prod FROM PRODUCTEUR WHERE Id_Uti= :Id_Uti ;'));
      $queryIdProd->bindParam(":Id_Uti", $utilisateur, PDO::PARAM_STR);
      $queryIdProd->execute();
      $returnQueryIdProd = $queryIdProd->fetchAll(PDO::FETCH_ASSOC);
      $Id_Prod=$returnQueryIdProd[0]["Id_Prod"];
    ?>

    <div class="container">
        <div class="leftColumn">
			<img class="logo" href="index.php" src="img/logo.png">
            <div class="contenuBarre">

            <div class="contenuBarre">
                <form action="ajouter_produit.php" method="get">
                    <button type="submit" class="bouton-ajout-produit"><?php echo $htmlAjouterProduit; ?></button>
                </form>
            </div>


            </div>
        </div>
        <div class="rightColumn">
            <div class="topBanner">
                <div class="divNavigation">
                <a class="bontonDeNavigation" href="index.php"><?php echo $htmlAccueil?></a>
                    <?php
                        if (isset($_SESSION["Id_Uti"])){
                            echo'<a class="bontonDeNavigation" href="messagerie.php">'.$htmlMessagerie.'</a>';
                            echo'<a class="bontonDeNavigation" href="achats.php">'.$htmlAchats.'</a>';
                        }
                        if (isset($_SESSION["isProd"]) and ($_SESSION["isProd"]==true)){
                            echo'<a class="bontonDeNavigation" href="produits.php">'.$htmlProduits.'</a>';
                            echo'<a class="bontonDeNavigation" href="delivery.php">'.$htmlCommandes.'</a>';
                        }
                        if (isset($_SESSION["isAdmin"]) and ($_SESSION["isAdmin"]==true)){
                            echo'<a class="bontonDeNavigation" href="panel_admin.php">'.$htmlPanelAdmin.'</a>';
                        }
                    ?>
                </div>
                <form method="post">
                    <?php
                    if(!isset($_SESSION)){
                    session_start();
                    }
                    if(isset($_SESSION, $_SESSION['tempPopup'])){
                        $_POST['popup'] = $_SESSION['tempPopup'];
                        unset($_SESSION['tempPopup']);
                    }
                    ?>

                    <input type="submit" value="<?php if (!isset($_SESSION['Mail_Uti'])){/*$_SESSION = array()*/; echo($htmlSeConnecter);} else {echo ''.$_SESSION['Mail_Uti'].'';}?>" class="boutonDeConnection">
                    <input type="hidden" name="popup" value=<?php if(isset($_SESSION['Mail_Uti'])){echo '"info_perso"';}else{echo '"sign_in"';}?>>
                
                </form>
            </div>

            


                    <!-- partie de gauche avec les produits -->
                    <div class="gallery-container">
                    <?php
                    // Connexion à la base de données
                    $bdd = dbConnect();
                    $queryGetProducts = $bdd->prepare(
                        'SELECT Id_Produit, Nom_Produit, Desc_Type_Produit, Prix_Produit_Unitaire, Nom_Unite_Prix, Qte_Produit 
                        FROM Produits_d_un_producteur 
                        WHERE Id_Prod = :Id_Prod;'
                    );
                    $queryGetProducts->bindParam(":Id_Prod", $Id_Prod, PDO::PARAM_INT);
                    $queryGetProducts->execute();
                    $returnQueryGetProducts = $queryGetProducts->fetchAll(PDO::FETCH_ASSOC);

                    // Tableau des traductions des unités
                    $unitesTrad = [
                        "Kg" => $htmlKg,
                        "L" => $htmlL,
                        "m²" => $htmlM2,
                        "Pièce" => $htmlPiece,
                        "le kilo" => $htmlLeKilo,
                        "la pièce" => $htmlLaPiece
                    ];

                    // Tableau des traductions des types de produits
                    $typeProduitsTrad = [
                        "Animaux" => $htmlAnimaux,
                        "Fruits" => $htmlFruits,
                        "Graines" => $htmlGraines,
                        "Légumes" => $htmlLegumes,
                        "Planches" => $htmlPlanches,
                        "Viande" => $htmlViande,
                        "Vin" => $htmlVin
                    ];

                    echo '<center><h2>' . $htmlMesProduitsEnStock . '</h2></center>';
                    echo '<div class="gallery-container">';

                    if (count($returnQueryGetProducts) == 0) {
                        echo "<p>$htmlAucunProduitEnStock</p>";
                    } else {
                        foreach ($returnQueryGetProducts as $produit) {
                            $Id_Produit = $produit["Id_Produit"];
                            $nomProduit = $produit["Nom_Produit"];
                            $typeProduit = trim($produit["Desc_Type_Produit"]);
                            $prixProduit = $produit["Prix_Produit_Unitaire"];
                            $QteProduit = $produit["Qte_Produit"];
                            $unitePrixProduit = trim($produit["Nom_Unite_Prix"]);
                    
                            // Normalisation et traduction
                            $typeProduit = ucfirst(strtolower($typeProduit));
                            $typeProduitTraduit = isset($typeProduitsTrad[$typeProduit]) ? $typeProduitsTrad[$typeProduit] : $typeProduit;
                            $uniteTraduit = isset($unitesTrad[$unitePrixProduit]) ? $unitesTrad[$unitePrixProduit] : $unitePrixProduit;
                    
                            // Détection du stock vide
                            $classStock = ($QteProduit == 0) ? ' out-of-stock' : '';
                    
                            echo '<div class="squareProduct' . $classStock . '">';
                            echo '<h3>' . $nomProduit . '</h3>';
                            echo '<p><strong>' . $htmlTypeDeuxPoints . '</strong> ' . $typeProduitTraduit . '</p>';
                            echo '<p><strong>' . $htmlPrix . '</strong> ' . $prixProduit . ' €/' . $uniteTraduit . '</p>';
                            echo '<p><strong>' . $htmlStockDeuxPoints . '</strong> ' . $QteProduit . ' ' . $uniteTraduit . '</p>';
                            
                            // Image du produit
                            echo '<img class="img-produit" src="img_produit/' . $Id_Produit  . '.png" alt="' . $htmlImageNonFournie . '" style="width: 100%; height: 85%;" ><br>';
                            
                            // Ajout du bouton "Modifier"
                            echo '<form action="product_modification.php" method="POST">';
                            echo '<input type="hidden" name="modifyIdProduct" value="' . $Id_Produit . '">';
                            echo '<button type="submit" class="btn-modify">' . $htmlModifier . '</button>';
                            echo '</form>';
                    
                            // Bouton de suppression (inchangé)
                            echo '<form action="delete_product.php" method="POST" onsubmit="return confirm(\'Êtes-vous sûr de vouloir supprimer ' . $nomProduit . ' ?\');">';
                            echo '<input type="hidden" name="Id_Produit" value="' . $Id_Produit . '">';
                            echo '<button type="submit" class="btn-delete">' . $htmlSupprimer . '</button>';
                            echo '</form>';
                    
                            echo '</div>';
                        }
                    }

                    

                    echo '</div>'; // Fin de la galerie de produits

                    ?>

                    </div>



            <div class="basDePage">
                <form method="post">
                <input type="submit" value="<?php echo $htmlSignalerDys?>" class="lienPopup">
                        <input type="hidden" name="popup" value="contact_admin">
				</form>
                <form method="post">
                <input type="submit" value="<?php echo $htmlCGU?>" class="lienPopup">
                        <input type="hidden" name="popup" value="cgu">
				</form>
            </div>
        </div>
    </div>
    <?php require "popups/gestion_popups.php";?>
</body>