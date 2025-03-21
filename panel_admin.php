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
    function dbConnect() {
        return new PDO('mysql:host=localhost;dbname=inf2pj_02;charset=utf8mb4', 'inf2pj02', 'ahV4saerae', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }
    $bdd = dbConnect();
    ?>
    <div class="container">
        <div class="leftColumn">
            <img class="logo" href="index.php" src="img/logo.png">
        </div>
        <div class="rightColumn">
            <div class="topBanner">
                <div class="divNavigation">
                    <a class="bontonDeNavigation" href="index.php"><?php echo $htmlAccueil; ?></a>
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

            <!-- ✅ Sections Admin regroupées dans un seul encadré -->
            <div class="admin-container">
                <div class="admin-section full-width">
                    <!-- ✅ Section Producteurs -->
                    <h2>👨‍🌾 Producteurs</h2>
                    <div class="card-container">
                        <?php
                        $conn = new mysqli("localhost", "inf2pj02", "ahV4saerae", "inf2pj_02");
                        if ($conn->connect_error) {
                            die("Erreur de connexion : " . $conn->connect_error);
                        }
                        $req = 'SELECT UTILISATEUR.Id_Uti, PRODUCTEUR.Prof_Prod, UTILISATEUR.Prenom_Uti, UTILISATEUR.Nom_Uti, UTILISATEUR.Mail_Uti, UTILISATEUR.Adr_Uti 
                                FROM PRODUCTEUR 
                                JOIN UTILISATEUR ON PRODUCTEUR.Id_Uti = UTILISATEUR.Id_Uti';
                        $stmt = $conn->prepare($req);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if (($result->num_rows > 0) && ($_SESSION["isAdmin"])) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<form method="post" action="traitements/del_acc.php" class="squarePanelAdmin">
                                        <input type="submit" onclick="return confirm(\'Confirmez la suppression.\')" value="Supprimer le compte">
                                        <input type="hidden" name="Id_Uti" value="' . $row["Id_Uti"] . '">';
                                echo "<p><strong>Nom :</strong> " . $row["Nom_Uti"] . "</p>";
                                echo "<p><strong>Prénom :</strong> " . $row["Prenom_Uti"] . "</p>";
                                echo "<p><strong>Mail :</strong> " . $row["Mail_Uti"] . "</p>";
                                echo "<p><strong>Adresse :</strong> " . $row["Adr_Uti"] . "</p>";
                                echo "<p><strong>Profession :</strong> " . $row["Prof_Prod"] . "</p></form>";
                            }
                        } else {
                            echo "<p>Aucun producteur trouvé.</p>";
                        }
                        ?>
                    </div>

                    <!-- ✅ Section Utilisateurs -->
                    <h2>👤 Utilisateurs</h2>
                    <div class="card-container">
                        <?php
                        $req = 'SELECT UTILISATEUR.Id_Uti, UTILISATEUR.Prenom_Uti, UTILISATEUR.Nom_Uti, UTILISATEUR.Mail_Uti, UTILISATEUR.Adr_Uti 
                                FROM UTILISATEUR 
                                WHERE UTILISATEUR.Id_Uti NOT IN (SELECT PRODUCTEUR.Id_Uti FROM PRODUCTEUR) 
                                AND UTILISATEUR.Id_Uti NOT IN (SELECT ADMINISTRATEUR.Id_Uti FROM ADMINISTRATEUR)';
                        $stmt = $conn->prepare($req);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($row = $result->fetch_assoc()) {
                            echo '<form method="post" action="traitements/del_acc.php" class="squarePanelAdmin">
                                    <input type="submit" onclick="return confirm(\'Confirmez la suppression.\')" value="Supprimer le compte">
                                    <input type="hidden" name="Id_Uti" value="' . $row["Id_Uti"] . '">';
                            echo "<p><strong>Nom :</strong> " . $row["Nom_Uti"] . "</p>";
                            echo "<p><strong>Mail :</strong> " . $row["Mail_Uti"] . "</p></form>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>