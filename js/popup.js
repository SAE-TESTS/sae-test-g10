document.addEventListener("DOMContentLoaded", function () {
    // Ouvrir la pop-up de modification avec les données du produit
    document.querySelectorAll(".btn-modifier").forEach(button => {
        button.addEventListener("click", function () {
            let idProduit = this.getAttribute("data-id");

            document.getElementById("popupIdProduit").value = idProduit;
            document.getElementById("popupNomProduit").value = this.getAttribute("data-nom");
            document.getElementById("popupTypeProduit").value = this.getAttribute("data-type");
            document.getElementById("popupPrixProduit").value = this.getAttribute("data-prix");
            document.getElementById("popupQuantiteProduit").value = this.getAttribute("data-quantite");
            
            document.getElementById("popupModification").style.display = "flex";
        });
    });

    // Gérer l'envoi du formulaire via AJAX pour éviter le rechargement de page
    document.getElementById("formModifierProduit").addEventListener("submit", function (event) {
        event.preventDefault();

        // Récupérer les données du formulaire
        let formData = new FormData(this);

        fetch("update_product.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())  
        .then(result => {
            console.log("Serveur :", result);

            // Rafraîchir la page après mise à jour réussie
            window.location.reload();
        })
        .catch(error => console.error("Erreur :", error));
    });
});

function fermerPopup() {
    document.getElementById("popupModification").style.display = "none";
}