
<?php

function clean($string)
{
    $string = str_replace(' ', '-', $string);
    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);

    return preg_replace('/-+/', '-', $string);
}

if (isset($_POST['new_nom'], $_POST['new_prenom'], $_POST['rue'], $_POST['code'], $_POST['ville'], $_POST['pwd'])) {
    $adr = $_POST['rue'] . ", " . $_POST['code'] . " " . mb_strtoupper($_POST['ville']);

    $utilisateur = "inf2pj02";
    $serveur = "localhost";
    $motdepasse = "ahV4saerae";
    $basededonnees = "inf2pj_02";
    $bdd = new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);

    if (!isset($_SESSION)) {
        session_start();
    }

    $update = "UPDATE UTILISATEUR SET Nom_Uti = '" . htmlspecialchars($_POST["new_nom"]) . "'," . "Prenom_Uti = '" . htmlspecialchars($_POST["new_prenom"]) . "'," . "Adr_Uti = '" . htmlspecialchars($adr) . "'," . "Pwd_Uti = '" . clean($_POST['pwd']) . "' WHERE Mail_Uti = '" . htmlspecialchars($_SESSION["Mail_Uti"]) . "';";

    echo ($update);
    $bdd->exec($update);

    header('Location: ../index.php');
} else {
    header('Location: ../index.php');
}
?>