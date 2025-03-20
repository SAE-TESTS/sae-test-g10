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
    if (!isset($_SESSION)) {
        session_start();
    }
    // variable utilisée plusieurs fois par la suite
    $Id_Prod = htmlspecialchars($_GET["Id_Prod"]);

    if (isset($_GET["filtreType"]) == true) {
        $filtreType = htmlspecialchars($_GET["filtreType"]);
    } else {
        $filtreType = "TOUT";
    }
    if (isset($_GET["tri"]) == true) {
        $tri = htmlspecialchars($_GET["tri"]);
    } else {
        $tri = "No";
    }
    if (isset($_GET["rechercheNom"]) == true) {
        $rechercheNom = htmlspecialchars($_GET["rechercheNom"]);
    } else {
        $rechercheNom = "";
    }
    ?>
    <div class="container">
        <div class="leftColumn">
            <img class="logo" href="index.php" src="img/logo.png">
            <div class="contenuBarre">
                <!-- some code -->

                <center>
                    <p><strong><?php echo $htmlRechercherPar; ?></strong></p>
                </center>
                <br>
                <form action="producteur.php" method="get">
                    <?php echo $htmlTiretNom; ?>
                    <input type="text" name="rechercheNom" value="<?php echo $rechercheNom ?>" placeholder="<?php echo $htmlNom; ?>">
                    <br>
                    <br>
                    - Type de produit :
                    <br>
                    <input type="hidden" name="Id_Prod" value="<?php echo $Id_Prod ?>">
                    <label>
                        <input type="radio" name="filtreType" value="TOUT" <?php if ($filtreType == "TOUT") echo 'checked="true"'; ?>> <?php echo $htmlTout; ?>
                    </label>
                    <br>
                    <label>
                        <input type="radio" name="filtreType" value="ANIMAUX" <?php if ($filtreType == "ANIMAUX") echo 'checked="true"'; ?>> <?php echo $htmlAnimaux; ?>
                    </label>
                    <br>
                    <label>
                        <input type="radio" name="filtreType" value="FRUITS" <?php if ($filtreType == "FRUITS") echo 'checked="true"'; ?>> <?php echo $htmlFruits; ?>
                    </label>
                    <br>
                    <label>
                        <input type="radio" name="filtreType" value="GRAINS" <?php if ($filtreType == "GRAINS") echo 'checked="true"'; ?>> <?php echo $htmlGraines; ?>
                    </label>
                    <br>
                    <label>
                        <input type="radio" name="filtreType" value="LÉGUMES" <?php if ($filtreType == "LÉGUMES") echo 'checked="true"'; ?>> <?php echo $htmlLégumes; ?>
                    </label>
                    <br>
                    <label>
                        <input type="radio" name="filtreType" value="PLANCHES" <?php if ($filtreType == "PLANCHES") echo 'checked="true"'; ?>> <?php echo $htmlPlanches; ?>
                    </label>
                    <br>
                    <label>
                        <input type="radio" name="filtreType" value="VIANDE" <?php if ($filtreType == "VIANDE") echo 'checked="true"'; ?>> <?php echo $htmlViande; ?>
                    </label>
                    <br>
                    <label>
                        <input type="radio" name="filtreType" value="VIN" <?php if ($filtreType == "VIN") echo 'checked="true"'; ?>> <?php echo $htmlVin; ?>
                    </label>
                    <br>
                    <br>
                    <br>
                    <?php echo $htmlTri; ?>
                    <select name="tri">
                        <option value="No" <?php if ($tri == "No") echo 'selected="selected"'; ?>><?php echo $htmlAucunTri; ?></option>
                        <option value="PrixAsc" <?php if ($tri == "PrixAsc") echo 'selected="selected"'; ?>><?php echo $htmlPrixCroissant; ?></option>
                        <option value="PrixDesc" <?php if ($tri == "PrixDesc") echo 'selected="selected"'; ?>><?php echo $htmlPrixDecroissant; ?></option>
                        <option value="Alpha" <?php if ($tri == "Alpha") echo 'selected="selected"'; ?>><?php echo $htmlOrdreAlpha; ?></option>
                        <option value="AntiAlpha" <?php if ($tri == "AntiAlpha") echo 'selected="selected"'; ?>><?php echo $htmlOrdreAntiAlpha; ?></option>
                    </select>
                    <br>
                    <br>
                    <center>
                        <input type="submit" value="<?php echo $htmlRechercher; ?>">
                    </center>
                </form>
                <br>
                <br>


            </div>
        </div>
        <div class="rightColumn">
            <div class="topBanner">
                <div class="divNavigation">
                    <a class="bontonDeNavigation" href="index.php"><?php echo $htmlAccueil ?></a>
                    <?php
                    if (isset($_SESSION["Id_Uti"])) {
                        echo '<a class="bontonDeNavigation" href="messagerie.php">' . $htmlMessagerie . '</a>';
                        echo '<a class="bontonDeNavigation" href="achats.php">' . $htmlAchats . '</a>';
                    }
                    if (isset($_SESSION["isProd"]) and ($_SESSION["isProd"] == true)) {
                        echo '<a class="bontonDeNavigation" href="produits.php">' . $htmlProduits . '</a>';
                        echo '<a class="bontonDeNavigation" href="delivery.php">' . $htmlCommandes . '</a>';
                    }
                    if (isset($_SESSION["isAdmin"]) and ($_SESSION["isAdmin"] == true)) {
                        echo '<a class="bontonDeNavigation" href="panel_admin.php">' . $htmlPanelAdmin . '</a>';
                    }
                    ?>
                </div>
                <form method="post">
                    <?php
                    if (!isset($_SESSION)) {
                        session_start();
                    }
                    if (isset($_SESSION, $_SESSION['tempPopup'])) {
                        $_POST['popup'] = $_SESSION['tempPopup'];
                        unset($_SESSION['tempPopup']);
                    }
                    ?>

                    <input type="submit" value="<?php if (!isset($_SESSION['Mail_Uti'])) {/*$_SESSION = array()*/;
                                                    echo ($htmlSeConnecter);
                                                } else {
                                                    echo '' . $_SESSION['Mail_Uti'] . '';
                                                } ?>" class="boutonDeConnection">
                    <input type="hidden" name="popup" value=<?php if (isset($_SESSION['Mail_Uti'])) {
                                                                echo '"info_perso"';
                                                            } else {
                                                                echo '"sign_in"';
                                                            } ?>>

                </form>
            </div>



            <form method="get" action="insert_commande.php">
                <input type="hidden" name="Id_Prod" value="<?php echo $Id_Prod ?>">

                <div class="content-container">
                    <div class="product">
                        <!-- partie de gauche avec les produits -->
                        <p>
                            <center><U><?php echo $htmlProduitsProposesDeuxPoints; ?></U></center>
                        </p>
                        <div class="gallery-container">
                            <?php
                            $bdd = dbConnect();
                            //filtre type
                            if ($filtreType == "TOUT") {
                                $query = 'SELECT Id_Produit, Id_Prod, Nom_Produit, Desc_Type_Produit, Prix_Produit_Unitaire, Nom_Unite_Prix, Qte_Produit FROM Produits_d_un_producteur  WHERE Id_Prod= :Id_Prod';
                            } else {
                                $query = 'SELECT Id_Produit, Id_Prod, Nom_Produit, Desc_Type_Produit, Prix_Produit_Unitaire, Nom_Unite_Prix, Qte_Produit FROM Produits_d_un_producteur  WHERE Id_Prod= :Id_Prod AND Desc_Type_Produit= :filtreType';
                            }
                            // filtre nom
                            if ($rechercheNom != "") {
                                $query = $query . ' AND Nom_Produit LIKE :rechercheNom ';
                            }

                            //tri
                            if ($tri == "No") {
                                $query = $query . ';';
                            } else if ($tri == "PrixAsc") {
                                $query = $query . ' ORDER BY Prix_Produit_Unitaire ASC;';
                            } else if ($tri == "PrixDesc") {
                                $query = $query . ' ORDER BY Prix_Produit_Unitaire DESC;';
                            } else if ($tri == "Alpha") {
                                $query = $query . ' ORDER BY Nom_Produit ASC;';
                            } else if ($tri == "AntiAlpha") {
                                $query = $query . ' ORDER BY Nom_Produit DESC;';
                            }

                            //preparation paramètres
                            $queryGetProducts = $bdd->prepare(($query));
                            if ($filtreType == "TOUT") {
                                $queryGetProducts->bindParam(":Id_Prod", $Id_Prod, PDO::PARAM_STR);
                            } else {
                                $queryGetProducts->bindParam(":Id_Prod", $Id_Prod, PDO::PARAM_STR);
                                $queryGetProducts->bindParam(":filtreType", $filtreType, PDO::PARAM_STR);
                            }
                            if ($rechercheNom != "") {
                                $queryGetProducts->bindParam(":rechercheNom", $rechercheNom, PDO::PARAM_STR);
                            }

                            $queryGetProducts->execute();
                            $returnQueryGetProducts = $queryGetProducts->fetchAll(PDO::FETCH_ASSOC);

                            // Tableau des traductions des unités
                            $unitesTrad = [
                                "Kg" => $htmlKg,
                                "kg" => $htmlKg,
                                "Kilo" => $htmlLeKilo,
                                "Le kilo" => $htmlLeKilo,
                                "L" => $htmlL,
                                "l" => $htmlL,
                                "m²" => $htmlM2,
                                "M²" => $htmlM2,
                                "Pièce" => $htmlPiece,
                                "piece" => $htmlPiece,
                                "la pièce" => $htmlLaPiece,
                                "Piece" => $htmlPiece
                            ];

                            $i = 0;
                            if (count($returnQueryGetProducts) == 0) {
                                echo $htmlAucunProduitEnStock;
                            } else {
                                while ($i < count($returnQueryGetProducts)) {
                                    $Id_Produit = $returnQueryGetProducts[$i]["Id_Produit"];
                                    $nomProduit = $returnQueryGetProducts[$i]["Nom_Produit"];
                                    $typeProduit = trim($returnQueryGetProducts[$i]["Desc_Type_Produit"]); // Nettoyage du type
                                    $prixProduit = $returnQueryGetProducts[$i]["Prix_Produit_Unitaire"];
                                    $QteProduit = $returnQueryGetProducts[$i]["Qte_Produit"];
                                    $unitePrixProduit = $returnQueryGetProducts[$i]["Nom_Unite_Prix"];

                                    // Vérifier si le produit est en rupture de stock pour ajouter une classe CSS spécifique
                                    $classStock = ($QteProduit == 0) ? ' out-of-stock' : '';

                                    // Normalisation de la casse
                                    $typeProduit = ucfirst(strtolower($typeProduit));

                                    // Vérification si la traduction existe dans le tableau
                                    $typeProduitTraduit = isset($typeProduitsTrad[$typeProduit]) ? $typeProduitsTrad[$typeProduit] : $typeProduit;
                                    $uniteTraduit = isset($unitesTrad[$unitePrixProduit]) ? $unitesTrad[$unitePrixProduit] : $unitePrixProduit;

                                    echo '<div class="squareProduct' . $classStock . '">';
                                    echo '<h3>' . $nomProduit . '</h3>';
                                    echo '<p><strong>' . $htmlTypeDeuxPoints . '</strong> ' . $typeProduitTraduit . '</p>';
                                    echo '<p><strong>' . $htmlPrix . '</strong> ' . $prixProduit . ' €/' . $uniteTraduit . '</p>';

                                    // Affichage du stock disponible
                                    echo '<p><strong>' . $htmlStockDeuxPoints . '</strong> ' . $QteProduit . ' ' . $uniteTraduit . '</p>';

                                    // Image du produit
                                    echo '<img class="img-produit" src="img_produit/' . $Id_Produit  . '.png" alt="' . $htmlImageNonFournie . '" style="width: 100%; height: 85%;" ><br>';

                                    // Input quantité (désactivé si stock à 0)
                                    if ($QteProduit > 0) {
                                        echo '<input type="number" class="input-quantity" name="' . $Id_Produit . '" placeholder="' . $htmlMaxStock . ' ' . $QteProduit . '" max="' . $QteProduit . '" min="0" value="0"> ' . $uniteTraduit;
                                    } else {
                                        echo '<input type="number" class="input-quantity disabled" name="' . $Id_Produit . '" placeholder="0" disabled> ' . $uniteTraduit;
                                        echo '<p class="rupture-message">⚠ ' . $htmlProduitEnRupture . '</p>';
                                    }

                                    echo '</div>';
                                    $i++;
                                }
                            }
                            ?>
                        </div>
                        <div class="centrecemachinbordeldemerde">
                            <button type="submit"><?php echo $htmlPasserCommande; ?></button>
                        </div>
                    </div>
                    <div class="producteur">
                        <!-- partie de droite avec les infos producteur -->
                        <?php
                        $bdd = dbConnect();
                        $queryInfoProd = $bdd->prepare(('SELECT UTILISATEUR.Id_Uti, UTILISATEUR.Adr_Uti, Prenom_Uti, Nom_Uti, Prof_Prod FROM UTILISATEUR INNER JOIN PRODUCTEUR ON UTILISATEUR.Id_Uti = PRODUCTEUR.Id_Uti WHERE PRODUCTEUR.Id_Prod= :Id_Prod ;'));
                        $queryInfoProd->bindParam(":Id_Prod", $Id_Prod, PDO::PARAM_STR);
                        $queryInfoProd->execute();
                        $returnQueryInfoProd = $queryInfoProd->fetchAll(PDO::FETCH_ASSOC);

                        // recupération des paramètres de la requête qui contient 1 élément
                        $idUti = $returnQueryInfoProd[0]["Id_Uti"];
                        $address = $returnQueryInfoProd[0]["Adr_Uti"];
                        $nom = $returnQueryInfoProd[0]["Nom_Uti"];
                        $prenom = $returnQueryInfoProd[0]["Prenom_Uti"];
                        $profession = $returnQueryInfoProd[0]["Prof_Prod"];
                        ?>
                        <div class="info-container">
                            <div class="img-prod">
                                <img class="img-test" src="img_producteur/<?php echo $Id_Prod; ?>.png" alt="<?php echo $htmlImgProducteur; ?>" style="width: 99%; height: 99%;">
                            </div>
                            <div class="text-info">
                                <?php
                                echo '</br>' . $prenom . ' ' . strtoupper($nom) . '</br></br><strong>' . $profession . '</strong></br></br>' . $address . '</br></br>';
                                ?>
                            </div>
                        </div>


                        <?php
                        //bloquer les 2 boutons pour les visiteurs non connectés
                        if (isset($_SESSION["Id_Uti"])  and $idUti != $_SESSION["Id_Uti"]) {
                        ?>
                            <input type="button" onclick="window.location.href='messagerie.php?Id_Interlocuteur=<?php echo $idUti; ?>'" value="<?php echo $htmlEnvoyerMessage; ?>">
                            <br>
                        <?php
                        } ?>


                        <?php
                        if (isset($address)) {
                            $address = str_replace(" ", "+", $address);
                        ?>
                            <iframe class="map-frame" src="https://maps.google.com/maps?&q=<?php echo $address; ?>&output=embed " width="100%" height="100%"></iframe>
                        <?php }

                        if (sizeof($returnQueryGetProducts) > 0 and isset($_SESSION["Id_Uti"]) and $idUti != $_SESSION["Id_Uti"]) {
                        ?>
                            <br>
                        <?php } ?>
            </form>
        </div>
    </div>



    <div class="basDePage">
        <form method="post">
            <input type="submit" value="<?php echo $htmlSignalerDys ?>" class="lienPopup">
            <input type="hidden" name="popup" value="contact_admin">
        </form>
        <form method="post">
            <input type="submit" value="<?php echo $htmlCGU ?>" class="lienPopup">
            <input type="hidden" name="popup" value="cgu">
        </form>
    </div>
    </div>
    </div>
    <?php require "popups/gestion_popups.php"; ?>
</body>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector("form[action='insert_commande.php']");
        const submitButton = form.querySelector("button[type='submit']");

        form.addEventListener("submit", function(event) {
            let hasSelection = false;
            const inputs = form.querySelectorAll("input[type='number']");

            inputs.forEach(input => {
                if (parseInt(input.value) > 0) {
                    hasSelection = true;
                }
            });

            if (!hasSelection) {
                event.preventDefault(); // Bloque l'envoi du formulaire
                alert("Veuillez sélectionner au moins un produit avant de passer commande.");
            }
        });
    });
</script>