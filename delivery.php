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
                <form action="delivery.php" method="post">
                    <label><input type="radio" name="typeCategorie" value="0" <?php echo $filtreCategorie == 0 ? 'checked' : ''; ?>> <?php echo $htmlTOUT; ?></label><br>
                    <label><input type="radio" name="typeCategorie" value="1" <?php echo $filtreCategorie == 1 ? 'checked' : ''; ?>> <?php echo $htmlENCOURS; ?></label><br>
                    <label><input type="radio" name="typeCategorie" value="2"<?php echo $filtreCategorie == 2 ? 'checked' : ''; ?>> <?php echo $htmlPRETE; ?></label><br>
                    <label><input type="radio" name="typeCategorie" value="4" <?php echo $filtreCategorie == 4 ? 'checked' : ''; ?>> <?php echo $htmlLIVREE; ?></label><br>
                    <label><input type="radio" name="typeCategorie" value="3" <?php echo $filtreCategorie == 3 ? 'checked' : ''; ?>> <?php echo $htmlANNULEE; ?></label><br><br>
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
                // Vérification de l'exécution correcte de la requête
                if (!isset($returnQueryGetCommande)) {
                    $query = 'SELECT COMMANDE.Id_Uti, Desc_Statut, Id_Commande, UTILISATEUR.Nom_Uti, UTILISATEUR.Prenom_Uti, COMMANDE.Id_Statut
                            FROM COMMANDE
                            INNER JOIN info_producteur ON COMMANDE.Id_Prod = info_producteur.Id_Prod
                            INNER JOIN STATUT ON COMMANDE.Id_Statut = STATUT.Id_Statut
                            INNER JOIN UTILISATEUR ON COMMANDE.Id_Uti = UTILISATEUR.Id_Uti
                            WHERE info_producteur.Id_Uti = :utilisateur';

                    if ($filtreCategorie != 0) {
                        $query .= ' AND COMMANDE.Id_Statut = :filtreCategorie';
                    }

                    $queryGetCommande = $bdd->prepare($query);
                    $queryGetCommande->bindParam(":utilisateur", $utilisateur, PDO::PARAM_INT);

                    if ($filtreCategorie != 0) {
                        $queryGetCommande->bindParam(":filtreCategorie", $filtreCategorie, PDO::PARAM_INT);
                    }
                    
                    try {
                        $queryGetCommande->execute();
                        $returnQueryGetCommande = $queryGetCommande->fetchAll(PDO::FETCH_ASSOC);
                    } catch (PDOException $e) {
                        echo "<p style='color: red;'>Erreur lors de l'exécution de la requête : " . $e->getMessage() . "</p>";
                        $returnQueryGetCommande = [];
                    }
                }

                // Vérification si des commandes existent
                if (empty($returnQueryGetCommande)) {
                    echo "<p>Aucune commande trouvée.</p>";
                } else {
                    foreach ($returnQueryGetCommande as $commande) {
                        $Id_Commande = $commande["Id_Commande"];
                        $Nom_Client = strtoupper($commande["Nom_Uti"]);
                        $Prenom_Client = $commande["Prenom_Uti"];
                        $Desc_Statut = strtoupper($commande["Desc_Statut"]);
                        $Id_Statut = $commande["Id_Statut"];

                        $classCommande = "commande";
                        if ($Id_Statut == 3) { 
                            $classCommande .= " annulee"; 
                            $modifiable = false; // Commande ANNULÉE => NON modifiable
                        } elseif ($Id_Statut == 4) { 
                            $classCommande .= " livree"; 
                            $modifiable = false; // Commande LIVRÉE => NON modifiable
                        } elseif ($Id_Statut == 1) { 
                            $classCommande .= " encours"; 
                            $modifiable = true;
                        } elseif ($Id_Statut == 2) { 
                            $classCommande .= " prete"; 
                            $modifiable = true;
                        }

                        echo "<div class='$classCommande'>";
                        echo "<h3>Commande n°$Id_Commande de $Prenom_Client $Nom_Client</h3>";
                        echo "<p class='infos'><strong>$Desc_Statut</strong></p>";

                        // Récupération des produits de la commande
                        $queryProduits = "SELECT Nom_Produit, Qte_Produit_Commande, Prix_Produit_Unitaire
                                        FROM produits_commandes
                                        WHERE Id_Commande = :idCommande";
                        $stmtProduits = $bdd->prepare($queryProduits);
                        $stmtProduits->bindParam(":idCommande", $Id_Commande, PDO::PARAM_INT);
                        $stmtProduits->execute();
                        $produits = $stmtProduits->fetchAll(PDO::FETCH_ASSOC);

                        echo "<ul>";
                        $total = 0;
                        foreach ($produits as $produit) {
                            $prixTotal = $produit["Qte_Produit_Commande"] * $produit["Prix_Produit_Unitaire"];
                            $total += $prixTotal;
                            echo "<li>{$produit["Nom_Produit"]} - {$produit["Qte_Produit_Commande"]} x {$produit["Prix_Produit_Unitaire"]}€ = <strong>$prixTotal €</strong></li>";
                        }
                        echo "</ul>";
                        echo "<p class='total'>Total : <strong>$total €</strong></p>";

                        // Affichage du formulaire de modification seulement si la commande est modifiable
                        if ($modifiable) {
                            echo "<form action='change_status_commande.php' method='post' class='form-status'>";
                            echo "<select name='categorie' class='command-select'>";
                            echo "<option value=''>Modifier statut</option>";
                            echo "<option value='1'>$htmlENCOURS</option>";
                            echo "<option value='2'>$htmlPRETE</option>";
                            echo "<option value='3'>$htmlANNULEE</option>";
                            echo "<option value='4'>$htmlLIVREE</option>";
                            echo "</select>";
                            echo "<button type='submit' class='btn-message'>$htmlConfirmer</button>";
                            echo "<input type='hidden' name='idCommande' value='$Id_Commande'>";
                            echo "</form>";
                        }

                        echo "</div>";
                    }
                }
                ?>
            </div>
        </div>
    </body>
</html>