<?php
    	function dbConnect(){
            $host = 'localhost';
            $dbname = 'inf2pj_02';
            $user = 'inf2pj02';
            $password = 'ahV4saerae';
            return new PDO('mysql:host='.$host.';dbname='.$dbname,$user,$password);
      }

	  $bdd=dbConnect();
	  session_start();
// Récupération des données de la commande
$Id_Commande = $_POST["idCommande"];
$queryGetCommande = $bdd->query(('SELECT Desc_Statut, Id_Commande, COMMANDE.Id_Uti, UTILISATEUR.Nom_Uti, UTILISATEUR.Prenom_Uti, COMMANDE.Id_Statut FROM COMMANDE INNER JOIN info_producteur ON COMMANDE.Id_Prod=info_producteur.Id_Prod INNER JOIN STATUT ON COMMANDE.Id_Statut=STATUT.Id_Statut INNER JOIN UTILISATEUR ON COMMANDE.Id_Uti=UTILISATEUR.Id_Uti WHERE COMMANDE.Id_Commande=' . $Id_Commande . ';'));
$returnQueryGetCommande = $queryGetCommande->fetchAll(PDO::FETCH_ASSOC);
$Id_Commande = $returnQueryGetCommande[0]["Id_Commande"];
$Desc_Statut = $returnQueryGetCommande[0]["Desc_Statut"];
$Nom_Producteur = $returnQueryGetCommande[0]["Nom_Prod"];

// Génération du contenu PDF
$pdfContent = '%PDF-1.3
1 0 obj
<< /Type /Catalog
/Pages 2 0 R >>
endobj
2 0 obj
<< /Type /Pages
/Kids [3 0 R]
/Count 1 >>
endobj
3 0 obj
<< /Type /Page
/Parent 2 0 R
/MediaBox [0 0 612 792]
/Resources << /Font << /F1 << /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >> >> >>
/Contents 4 0 R >>
endobj
4 0 obj
<< /Length 300 >>
stream
BT
/F1 18 Tf
50 750 Td
(Bon de Commande) Tj
/F1 12 Tf
50 730 Td
(--------------------------------------------) Tj
50 710 Td
(Commande ID: ' . $Id_Commande . ') Tj
50 690 Td
(Statut: ' . $Desc_Statut . ') Tj
50 670 Td
(Producteur: ' . $Nom_Producteur . ') Tj
50 650 Td
(--------------------------------------------) Tj
50 630 Td
(Nombre de produits: 3) Tj
50 610 Td
(Produit 1: T-shirt) Tj
50 590 Td
(Quantité: 5) Tj
50 570 Td
(Prix unitaire: $10) Tj
50 550 Td
(--------------------------------------------) Tj
50 530 Td
(Produit 2: Tomates) Tj
50 510 Td
(Quantité: 3) Tj
50 490 Td
(Prix unitaire: $5) Tj
50 470 Td
(--------------------------------------------) Tj
50 450 Td
(Produit 3: Chaussures) Tj
50 430 Td
(Quantité: 2) Tj
50 410 Td
(Prix unitaire: $50) Tj
50 390 Td
(--------------------------------------------) Tj
ET
endstream
endobj
xref
0 5
0000000000 65535 f 
0000000018 00000 n 
0000000077 00000 n 
0000000178 00000 n 
0000000653 00000 n 
trailer
<< /Root 1 0 R
/Size 5 >>
startxref
622
%%EOF
';

// Envoie les entêtes pour indiquer que le contenu est un PDF à télécharger
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename=bill.pdf');

// Sortie du contenu PDF généré
echo $pdfContent;
?>