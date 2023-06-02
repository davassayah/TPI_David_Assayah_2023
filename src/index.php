<?php

/**
 * ETML
 * Auteur: David Assayah
 * Date: 23.05.2023
 * Description: Page d'accueil où les informations des cartes enregistrées sont visibles et où il est possible de consulter, modifier ou supprimer une carte
 * selon que l'on soit utilisateur ou administrateur.
 */

include("header.php");


//permet de connecter la méthode se trouvant dans la page database.php
$cards = $db->getAllCards();

$collections = $db->getAllCollections();

//Récupère l'id de l'enseignant dans l'url pour le supprimer
if (isset($_GET['idCard']) and $id = $_GET['idCard']) {
    $db->deleteCardById($id);
    header('Location: index.php');
}

if (isset($_GET['idCardToAddInCart']) and $idCard = $_GET['idCardToAddInCart']) {
    if (!isset($_SESSION['panier'][$idCard])) {
        $index = array_search($idCard, array_column($cards, 'idCard'));
        $_SESSION['panier'][$idCard] = $cards[$index];
    }
}

// Vérifie si le formulaire a été soumis
if (isset($_GET['submit'])) {
    // Récupère la valeur entrée dans le champ de texte
    $cards = $db->sortCards($_GET);
}

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

<?php

if (isset($_SESSION['successMessage'])) {
    $message = $_SESSION['successMessage'];
    echo '<div style="display: flex; justify-content: center; align-items: center; height: 100vh;">
              <h1 style="font-size: 3em;">' . $message . '</h1>
          </div>';
    include("footer.php");
    unset($_SESSION['successMessage']);
    exit();
} else if (isset($_SESSION['connexionError'])) {
    $message = $_SESSION['connexionError'];
    echo '<div style="display: flex; justify-content: center; align-items: center; height: 100vh;">
              <h1 style="font-size: 3em;"> ' . $message . '</h1>
          </div>';
    include("footer.php");
    unset($_SESSION['connexionError']);
    exit();
} else if (!isset($_SESSION['userConnected'])) {
    echo '<div style="display: flex; justify-content: center; align-items: center; height: 100vh;">
              <h1 style="font-size: 3em;">Bienvenue! Crée un compte ou connecte-toi</h1>
          </div>';
    include("footer.php");
    exit();
}

?>

<body>

    <div class="container">
        <fieldset class="help mb-2 mt-5">
            <h5>Filtres</h5>
            <form method="GET" action="" class="row g-3">
                <div class="col-2">
                    <label for="search" class="form-label">Nom</label>
                    <input type="text" name="search" id="search" class="form-control">
                </div>
                <div class="col-2">
                    <button type="button" id="more-filters-btn" class="btn btn-sm btn-primary">Plus de filtres</button>
                </div>
                <div id="filter-collection" class="col-2 d-none">
                    <label for="idCollection" class="form-label">Collection</label>
                    <select name="idCollection" id="idCollection" class="form-select" aria-label="Default select example">
                        <option value="">Collection</option>
                        <?php foreach ($collections as $collection) { ?>
                            <option value="<?php echo $collection["idCollection"] ?>"><?php echo $collection["colName"] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div id="filter-condition" class="col-2 d-none">
                    <label for="condition" class="form-label">Etat</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="new" value="N" name="conditions[]">
                        <label class="form-check-label" for="new">Neuf</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="secondHand" value="O" name="conditions[]">
                        <label class="form-check-label" for="secondHand">Occasion</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="damaged" value="A" name="conditions[]">
                        <label class="form-check-label" for="damaged">Abîmé</label>
                    </div>
                </div>
                <div class="col-2">
                    <input type="submit" name="submit" value="Rechercher" class="btn btn-success btn-sm">
                </div>
            </form>

            <h3 class="mb-3">Liste des cartes</h3>

            <form action="#" method="post">
                <table id="sortTable" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="th-sm">
                                Nom
                            </th>
                            <th class="th-sm">
                                Date de création
                            </th>
                            <th class="th-sm">
                                Crédits
                            </th>
                            <th class="th-sm">
                                Etat
                            </th>
                            <th class="th-sm">
                                Possesseur
                            </th>
                            <th class="th-sm">
                                Collection
                            </th>
                            <th class="th-sm">
                                Options
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cards as $card) { ?>
                            <tr>
                                <td><?php echo $card["carName"] ?></td>
                                <td><?php echo $card["carDate"] ?></td>
                                <td><?php echo $card["carCredits"] ?></td>
                                <td>
                                    <?php if ($card["carCondition"] == "N") {
                                        echo "Neuf";
                                    } else if ($card["carCondition"] == "O") {
                                        echo  "Occasion";
                                    } else if ($card["carCondition"] == "A") {
                                        echo "Abîmé";
                                    } ?>
                                </td>
                                <td><?php echo $card["carUserLogin"] ?></td>
                                <td><?php echo $card["carCollectionName"] ?></td>
                                <td class="containerOptions">
                                    <?php if (isset($_SESSION['userConnected'])) { ?>
                                        <a class="btn btn-dark btn-sm" href="cardDetails.php?idCard=<?php echo $card["idCard"] ?>">Détails</a>
                                        <?php if ($_SESSION['userConnected'] == 'admin' || ($_SESSION['userConnected'] == 'user' && $_SESSION['useLogin'] == $card['carUserLogin'])) { ?>
                                            <a class="btn btn-warning btn-sm" href="updateCard.php?idCard=<?php echo $card["idCard"]; ?>">Modifier</a>
                                            <a class="btn btn-danger btn-sm" href="javascript:confirmDelete(<?php echo $card["idCard"] ?>)">Supprimer</a>
                                        <?php } ?>
                                        <?php if (($_SESSION['userConnected'] == 'admin' || $_SESSION['userConnected'] == 'user') && $_SESSION['useLogin'] != $card['carUserLogin']) { ?>
                                            <a class="btn btn-success btn-sm" href="javascript:confirmBuy(<?php echo $card["idCard"] ?>)">Acheter</a>
                                        <?php } ?>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>

                </table>
            </form>
            <script src="js/script.js"></script>
    </div>
    </fieldset>
    </div>

    <script>
        $(document).ready(function() {
            $('#sortTable').DataTable({
                searching: false,
                language: {
                    lengthMenu: "Montrer _MENU_ entrées",
                    info: "_TOTAL_ résultat.s trouvé.s",
                    paginate: {
                        next: "Suivant",
                        previous: "Précédent"
                    }
                }
            });

            // Afficher/Cacher les filtres en fonction du bouton "Plus de filtres"
            $('#more-filters-btn').click(function() {
                $('#filter-collection').toggleClass('d-none');
                $('#filter-condition').toggleClass('d-none');
            });
        });
    </script>

</body>

</html>

<?php include("footer.php"); ?>