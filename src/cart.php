<?php

include("header.php");

if (!isset($_SESSION['userConnected']) || $_SESSION['userConnected'] != ('user' or 'admin')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    require_once(__DIR__ . "/403.php");
    exit;
}

echo "panier"


?>

<?php
session_start();
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
            <?php include('header.php'); ?>
            <section style="min-height: calc(100vh - 20%);">
                  <h1><?php echo $traduction[$_SESSION["lang"]]["votrePanier"] ?></h1>
                  <table>
                        <thead>
                              <tr>
                                    <th><?php echo $traduction[$_SESSION["lang"]]["carteNom"] ?></th>
                                    <th><?php echo $traduction[$_SESSION["lang"]]["prixUnite"] ?></th>
                                    <th><?php echo $traduction[$_SESSION["lang"]]["quantite"] ?></th>
                                    <th><?php echo $traduction[$_SESSION["lang"]]["prixTotal"] ?></th>
                              </tr>
                        </thead>
                        <tbody>
                              <!-- foreach permettant d'afficher dans un tableau les informations relatives aux articles ajoutés au panier-->
                              <?php foreach($_SESSION['panier'] as $carteId => $data) { ?>
                                    <tr>
                                          <td><?php echo $data['data']['description'] ?></td>
                                          <td><?php echo $data['data']['price'] ?></td>
                                          <td><?php echo $data['count'] ?></td>
                                          <td><?php echo $data['data']['price'] * $data['count'] ?></td>
                                    </tr>
                              <?php } ?>
                        </tbody>
                  </table>
            </section>
            <?php include('footer.php'); ?>
      </body>
</html>