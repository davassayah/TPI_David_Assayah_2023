<?php

/**
 * ETML
 * Auteur: David Assayah
 * Date: 24.05.2023
 * Description: Page permettant d'afficher toutes les informations d'un utilisateur et de valider les commandes si elles sont en attente
 */

include("header.php");

if (!isset($_SESSION['userConnected']) || $_SESSION['userConnected'] != 'user' or 'admin') {
    header('HTTP/1.0 403 Forbidden', true, 403);
    require_once(__DIR__ . "/403.php");
    exit;
}

$OneUser = $db->getOneUser($_GET["idUser"]);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="./css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
    <title>Echange de cartes à collectionner</title>
</head>

<body>
    <fieldset class="mb-3 mt-5">
        <div class="container">
            <div class="user-body">

                <h3>Informations de l'utilisateur : </h3> 

                <?php echo 
                "Login : " . $OneUser["useLogin"] . "<br>" . 
                "Email : " . $OneUser["useEmail"] . "<br>" . 
                "Prénom : " . $OneUser["useFirstName"] . "<br>" . 
                "Nom : " . $OneUser["useLastName"] . "<br>" . 
                "Localité : " . $OneUser["useLocality"] . "<br>" . 
                "Code Postal : " . $OneUser["usePostalCode"] . "<br>" . 
                "Nom de la Rue : " . $OneUser["useStreetName"] . "<br>" .
                "Numéro de la Rue : " . $OneUser["useStreetNumber"] . "<br>"
                ?>
                <div class="confirmOrder"></div>
                <!--Gestion de l'état de la commande. Si aucune commande en attente affiche "Aucune commande à confirmer". Si commande en attente affiche un bouton "Confirmer la réception"-->
                <button>Confirmer la réception</button>
            </div>
        </div>
        </form>
        <script src="js/script.js"></script>
        </div>
    </fieldset>
    </div>

</body>

</html>