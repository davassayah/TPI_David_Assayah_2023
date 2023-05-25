// Affiche un popup pour confirmer la suppression d'une carte
function confirmDelete(cardId) {
    if (confirm("Êtes-vous sûr de vouloir supprimer la carte?") === true) {
        window.location.href = window.location.href + '?idCard=' + cardId;
   
    }
}

function confirmBuy(cardId) {
    if (confirm("Êtes-vous sûr de vouloir ajouter cette carte au panier ?")) {
        // Redirige vers la page cart.php si l'utilisateur clique sur "Oui"
        window.location.href = "cart.php?idCard=" + cardId;
    } else {
        // Actions à effectuer si l'utilisateur clique sur "Non"
        window.location.href = "index.php";
    }
}

