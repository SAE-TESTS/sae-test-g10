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
    <link rel="stylesheet" type="text/css" href="css/admin_style.css"> <!-- Ajout d'un fichier CSS dédié -->
</head>
<body>
    <?php
        if(!isset($_SESSION)){
            session_start();
        }
    ?>
    <div class="container">
        <!-- COLONNE GAUCHE -->
        <div class="leftColumn">
            <a href="index.php"><img class="logo" src="img/logo.png"></a>
        </div>

        <!-- COLONNE DROITE -->
        <div class="rightColumn">
            <!-- HEADER -->
            <div class="topBanner">
                <div class="divNavigation">
                    <a class="bontonDeNavigation" href="index.php"><?php echo $htmlAccueil?></a>
                    <?php
                        if (isset($_SESSION["Id_Uti"])) {
                            echo '<a class="bontonDeNavigation" href="messagerie.php">'.$htmlMessagerie.'</a>';
                            echo '<a class="bontonDeNavigation" href="achats.php">'.$htmlAchats.'</a>';
                        }
                        if (isset($_SESSION["isProd"]) && $_SESSION["isProd"] == true) {
                            echo '<a class="bontonDeNavigation" href="produits.php">'.$htmlProduits.'</a>';
                            echo '<a class="bontonDeNavigation" href="delivery.php">'.$htmlCommandes.'</a>';
                        }
                        if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == true) {
                            echo '<a class="bontonDeNavigation" href="panel_admin.php">'.$htmlPanelAdmin.'</a>';
                        }
                    ?>
                </div>
                <form method="post">
                    <?php
                    if(isset($_SESSION, $_SESSION['tempPopup'])){
                        $_POST['popup'] = $_SESSION['tempPopup'];
                        unset($_SESSION['tempPopup']);
                    }
                    ?>
                    <?php
                    if (isset($_SESSION["isAdmin"]) and ($_SESSION["isAdmin"] == true)) {
                        echo '<div class="dropdown">
                                <button class="dropbtn">Broadcast</button>
                                <div class="dropdown-content">
                                    <a href="broadcastuser.php">'.$htmlbroadcastuser.'</a>
                                    <a href="broadcastprod.php">'.$htmlbroadcastprod.'</a>
                                </div>
                            </div>';
                    }
                    ?>
                    <input type="submit" value="<?php echo isset($_SESSION['Mail_Uti']) ? $_SESSION['Mail_Uti'] : $htmlSeConnecter; ?>" class="boutonDeConnection">
                    <input type="hidden" name="popup" value="<?php echo isset($_SESSION['Mail_Uti']) ? 'info_perso' : 'sign_in'; ?>">
                </form>
            </div>

            <!-- CONTENU PRINCIPAL AVEC STYLE AMÉLIORÉ -->
            <div class="admin-container">
                <div class="admin-card">
                    <h2 class="admin-title"><?php echo $htmlEnvoyerMessageATousUti; ?></h2>
                    <form action="traitements/traitement_broadcast_user.php" method="post">
                        <label for="message" class="admin-label"><?php echo $htmlVotreMessage; ?></label>
                        <textarea id="message" name="message" rows="5" maxlength="5000" required class="admin-textarea"></textarea>
                        <br>
                        <input type="submit" value="<?php echo $htmlEnvoyerMessageATousUti; ?>" class="admin-button">
                    </form>
                </div>
            </div>

            <!-- BAS DE PAGE -->
            <div class="admin-footer">
                <form method="post">
                    <input type="submit" value="<?php echo $htmlSignalerDys?>" class="admin-footer-button">
                    <input type="hidden" name="popup" value="contact_admin">
                </form>
                <form method="post">
                    <input type="submit" value="<?php echo $htmlCGU?>" class="admin-footer-button">
                    <input type="hidden" name="popup" value="cgu">
                </form>
            </div>
        </div>
    </div>
    <?php require "popups/gestion_popups.php";?>
</body>
</html>