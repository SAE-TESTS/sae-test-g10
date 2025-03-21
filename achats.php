<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require "language.php"; ?>
    <title><?php echo $htmlMarque; ?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style_general.css">
    <link rel="stylesheet" type="text/css" href="css/popup.css">
</head>
<body>
    <?php
    if (!isset($_SESSION)) { session_start(); }

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

    $bdd = dbConnect();
    $utilisateur = htmlspecialchars($_SESSION["Id_Uti"]);
    $filtreCategorie = isset($_POST["typeCategorie"]) ? htmlspecialchars($_POST["typeCategorie"]) : 0;
    ?>

    <div class="container">
        <div class="leftColumn">
            <img class="logo" href="index.php" src="img/logo.png">
            <div class="contenuBarre">
                <center><p><strong><?php echo $htmlFiltrerParDeuxPoints; ?></strong></p></center>
                <form action="achats.php" method="post">
                    <label><input type="radio" name="typeCategorie" value="0" <?php if ($filtreCategorie == 0) echo 'checked';?>> <?php echo $htmlTOUT; ?></label><br>
                    <label><input type="radio" name="typeCategorie" value="1" <?php if ($filtreCategorie == 1) echo 'checked';?>> <?php echo $htmlENCOURS; ?></label><br>
                    <label><input type="radio" name="typeCategorie" value="2"<?php if ($filtreCategorie == 2) echo 'checked';?>> <?php echo $htmlPRETE; ?></label><br>
                    <label><input type="radio" name="typeCategorie" value="4" <?php if ($filtreCategorie == 4) echo 'checked';?>> <?php echo $htmlLIVREE; ?></label><br>
                    <label><input type="radio" name="typeCategorie" value="3" <?php if ($filtreCategorie == 3) echo 'checked';?>> <?php echo $htmlANNULEE; ?></label><br><br>
                    <center><input type="submit" value="<?php echo $htmlFiltrer; ?>"></center>
                </form>
            </div>
        </div>

        <div class="rightColumn">
            <div class="topBanner">
                <div class="divNavigation">
                    <a class="bontonDeNavigation" href="index.php"><?php echo $htmlAccueil?></a>
                    <?php
                        if (isset($_SESSION["Id_Uti"])) {
                            echo '<a class="bontonDeNavigation" href="messagerie.php">' . $htmlMessagerie . '</a>';
                            echo '<a class="bontonDeNavigation" href="achats.php">' . $htmlAchats . '</a>';
                        }
                        if (!empty($_SESSION["isProd"])) {
                            echo '<a class="bontonDeNavigation" href="produits.php">' . $htmlProduits . '</a>';
                            echo '<a class="bontonDeNavigation" href="delivery.php">' . $htmlCommandes . '</a>';
                        }
                        if (!empty($_SESSION["isAdmin"])) {
                            echo '<a class="bontonDeNavigation" href="panel_admin.php">' . $htmlPanelAdmin . '</a>';
                        }
                    ?>
                </div>
                <form method="post">
                    <input type="submit" value="<?php echo isset($_SESSION['Mail_Uti']) ? $_SESSION['Mail_Uti'] : $htmlSeConnecter; ?>" class="boutonDeConnection">
                    <input type="hidden" name="popup" value="<?php echo isset($_SESSION['Mail_Uti']) ? 'info_perso' : 'sign_in'; ?>">
                </form>
            </div>

            <div class="contenuPage">
                <?php
                    $query = 'SELECT PRODUCTEUR.Id_Uti, Desc_Statut, Id_Commande, Nom_Uti, Prenom_Uti, Adr_Uti, COMMANDE.Id_Statut FROM COMMANDE 
                              INNER JOIN PRODUCTEUR ON COMMANDE.Id_Prod=PRODUCTEUR.Id_Prod 
                              INNER JOIN info_producteur ON COMMANDE.Id_Prod=info_producteur.Id_Prod 
                              INNER JOIN STATUT ON COMMANDE.Id_Statut=STATUT.Id_Statut 
                              WHERE COMMANDE.Id_Uti = :utilisateur';

                    if ($filtreCategorie != 0) {
                        $query .= ' AND COMMANDE.Id_Statut = :filtreCategorie';
                    }

                    $queryGetCommande = $bdd->prepare($query);
                    $queryGetCommande->bindParam(":utilisateur", $utilisateur, PDO::PARAM_STR);

                    if ($filtreCategorie != 0) {
                        $queryGetCommande->bindParam(":filtreCategorie", $filtreCategorie, PDO::PARAM_INT);
                    }

                    $queryGetCommande->execute();
                    $returnQueryGetCommande = $queryGetCommande->fetchAll(PDO::FETCH_ASSOC);

                    if (!$returnQueryGetCommande) {
                        echo $htmlAucuneCommande . "<br>";
                        echo '<input type="button" onclick="window.location.href=\'index.php\'" value="' . $htmlDecouverteProducteurs . '">';
                    } else {
                        foreach ($returnQueryGetCommande as $commande) {
                            $Id_Commande = $commande["Id_Commande"];
                            $Nom_Prod = strtoupper($commande["Nom_Uti"]);
                            $Prenom_Prod = $commande["Prenom_Uti"];
                            $Adr_Uti = $commande["Adr_Uti"];
                            $Desc_Statut = strtoupper($commande["Desc_Statut"]);
                            $Id_Statut = $commande["Id_Statut"];
                            $idUti = $commande["Id_Uti"];
                            
                            $classCommande = "commande";
                            if ($Id_Statut == 3) { $classCommande .= " annulee"; }   
                            elseif ($Id_Statut == 4) { $classCommande .= " livree"; }   
                            elseif ($Id_Statut == 1) { $classCommande .= " encours"; }  
                            elseif ($Id_Statut == 2) { $classCommande .= " prete"; }   

                            echo '<div class="' . $classCommande . '">';
                            echo "<h3>Commande n°$Id_Commande : Chez $Prenom_Prod $Nom_Prod</h3>";
                            echo "<p class='infos'><strong>$Desc_Statut</strong> - $Adr_Uti</p>";

                            echo '<input type="button" class="btn-message" onclick="window.location.href=\'messagerie.php?Id_Interlocuteur=' . $idUti . '\'" value="' . $htmlEnvoyerMessage . '">';

                            if ($Id_Statut != 3 && $Id_Statut != 4) {
                                echo '<form action="delete_commande.php" method="post" class="btn-action">';
                                echo '<input type="hidden" name="deleteValeur" value="' . $Id_Commande . '">';
                                echo '<button type="submit" class="btn-danger">' . $htmlAnnulerCommande . '</button>';
                                echo '</form>';
                            }
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
    
                <div class="basDePage">
                    <form method="post">
                        <input type="submit" value="<?php echo $htmlSignalerDys; ?>" class="lienPopup">
                        <input type="hidden" name="popup" value="contact_admin">
                    </form>
    
                    <form method="post">
                        <input type="submit" value="<?php echo $htmlCGU; ?>" class="lienPopup">
                        <input type="hidden" name="popup" value="cgu">
                    </form>
                </div>
            </div>
        </div>
    
        <?php require "popups/gestion_popups.php"; ?>
    </body>
    </html>