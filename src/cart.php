<?php

/**
 * ETML
 * Auteur: David Assayah
 * Date: 30.05.2023
 * Description: Page de panier permettant de créer une commande en fonction des cartes ajoutées au panier
 */

include("header.php");

if (!isset($_SESSION['userConnected']) || $_SESSION['userConnected'] != ('user' or 'admin')) {
      header('HTTP/1.0 403 Forbidden', true, 403);
      require_once(__DIR__ . "/403.php");
      exit;
}

// Lorsque l'on supprime une carte du panier
if (isset($_GET['idCard']) and $idCard = $_GET['idCard']) {
      unset($_SESSION['panier'][$idCard]);
}

$error = null;
if (isset($_GET['buy']) and $_GET['buy'] == 'true') {
      $isOrderCreated = $db->createOrder($_SESSION['idUser'], array_keys($_SESSION['panier']));

      if ($isOrderCreated) {
            $_SESSION['useCredits'] = $db->getOneUser($_SESSION['idUser'])['useCredits'];
            $_SESSION['panier'] = [];
      } else {
            $error = "L'utilisateur n'a pas suffisamment de credits pour passer la commande.";
      }
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

<body>
      <section style="min-height: calc(100vh - 20%);">
            <div class="container">
                  <h2>Panier</h2>
                  <?php if ($error != null) { ?>
                        <div class="alert alert-danger" role="alert">
                              <?php echo $error ?>
                        </div>
                  <?php } ?>
                  <h3 class="mb-3">Liste des cartes</h3>
                  <form action="cart.php" method="post">
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
                                    <?php foreach ($_SESSION['panier'] as $card) { ?>
                                          <tr>
                                                <td><?php echo $card["carName"] ?></td>
                                                <td><?php echo $card["carDate"] ?></td>
                                                <td><?php echo $card["carCredits"] ?></td>
                                                <td><?php if ($card["carCondition"] == "N") {
                                                            echo "Neuf";
                                                      } else if ($card["carCondition"] == "O") {
                                                            echo  "Occasion";
                                                      } else if ($card["carCondition"] == "A") {
                                                            echo "Abîmé";
                                                      } ?></td>
                                                <td><?php echo $card["carUserLogin"] ?></td>
                                                <td><?php echo $card["carCollectionName"] ?></td>
                                                <td class="containerOptions">
                                                      <!--Affiche différentes fonctionnalités selon que l'utilisateur soit connecté en tant qu'utilisateur ou en tant qu'admin-->
                                                      <?php if (isset($_SESSION['userConnected']) && $_SESSION['userConnected'] == ('user' or 'admin')) { ?>
                                                            <?php if (isset($_SESSION['userConnected'])) { ?>
                                                                  <a class="link-light" href="javascript:confirmDeleteFromCart(<?php echo $card["idCard"] ?>)">
                                                                        <img height="40em" src="./img/delete.png" alt="delete">
                                                                  </a>
                                                            <?php } ?>
                                                            <a class="link-light" href="cardDetails.php?idCard=<?php echo $card["idCard"] ?>">
                                                                  <img height="40em" src="./img/details.jpg" alt="detail">
                                                            </a>
                                                      <?php } ?>
                                                </td>
                                          </tr>
                                    <?php } ?>
                              </tbody>
                        </table>
                        <?php if (count($_SESSION['panier']) > 0) { ?>
                              <a class="btn btn-primary" href="cart.php?buy=true" role="button">Confirmer la commande</a>
                        <?php } ?>
                  </form>
            </div>

      </section>
      <script src="js/script.js"></script>
      <?php include('footer.php'); ?>
</body>

</html>