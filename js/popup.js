document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".btn-modifier").forEach(button => {
        button.addEventListener("click", function () {
            document.getElementById("popupIdProduit").value = this.getAttribute("data-id");
            document.getElementById("popupNomProduit").value = this.getAttribute("data-nom");
            document.getElementById("popupTypeProduit").value = this.getAttribute("data-type");
            document.getElementById("popupPrixProduit").value = this.getAttribute("data-prix");
            document.getElementById("popupQuantiteProduit").value = this.getAttribute("data-quantite");

            document.getElementById("popupModification").style.display = "flex";
        });
    });
});

function fermerPopup() {
    document.getElementById("popupModification").style.display = "none";
}