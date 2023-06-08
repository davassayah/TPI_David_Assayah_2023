// Affiche un popup pour confirmer la suppression d'une carte
function confirmDelete(cardId) {
    if (confirm("Êtes-vous sûr de vouloir supprimer la carte?") === true) {
        window.location.href = window.location.href + '?idCard=' + cardId;
   
    }
}

function confirmDeleteFromCart(cardId) {
    if (confirm("Êtes-vous sûr de vouloir supprimer la carte de votre panier?") === true) {
        window.location.href = "cart.php?idCard=" + cardId;
    }
}

function confirmBuy(cardId) {
    if (confirm("Êtes-vous sûr de vouloir ajouter cette carte au panier ?")) {
        // Ajoute la carte au panier en redirigeant vers la page index.php avec l'identifiant de la carte
        window.location.href = "index.php?idCardToAddInCart=" + cardId;
        if (confirm("Souhaitez-vous vous rendre sur la page du panier maintenant ?")) {
            // Redirige vers la page cart.php si l'utilisateur clique sur "Oui"
            window.location.href = "cart.php";
        }
    } else {
        // Redirige vers la page index.php sans ajouter la carte au panier
        window.location.href = "index.php";
    }
}

function confirmOrderReceptionFromUser(userId, orderId) {
    if (confirm("Êtes-vous sûr de vouloir confirmer la reception de la commande ?")) {
        window.location.href = `userProfile.php?idUser=${userId}&idOrderToConfirm=${orderId}`;
    }
}

function showSuccessAddCardMessage() {
    alert("La carte a été ajoutée avec succès!");
  
    var response = confirm("Que souhaitez-vous faire maintenant?\n\n- Cliquez sur OK pour ajouter une nouvelle carte.\n- Cliquez sur Annuler pour retourner à la page d'accueil.");
  
    if (response) {
      window.location.href = "addCard.php";
    } else {
      window.location.href = "index.php";
    }
  }

  function confirmOrder() {
    if (confirm("Êtes-vous sûr de vouloir passer commande ?")) {
    }
  }
  
  
  
  