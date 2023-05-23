// Affiche un popup pour confirmer la suppression d'une carte
function confirmDelete(cardId) {
    if (confirm("Êtes-vous sûr de vouloir supprimer la carte?") === true) {
        window.location.href = window.location.href + '?idCard=' + cardId;
   
    }
}

