<?php

/**
 * ETML
 * Auteur: David Assayah
 * Date: 23.05.2023
 * Description: Page permettant d'afficher toutes les informations d'une carte
 */

include("header.php");

if (!isset($_SESSION['userConnected']) || $_SESSION['userConnected'] != ('user' or 'admin')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    require_once(__DIR__ . "/403.php");
    exit;
}

//Récupère les informations de la carte via son id qui se trouve dans l'url
$oneCard = $db->getOneCard($_GET["idCard"]);
$cards = $db->getAllCards();
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
                <h1>Informations de la carte : </h1>

                <div class="photo">
                    <img height="600em" src="<?php echo $oneCard['carPhoto'] ?>">
                </div>
                <?php
                echo '<div style="font-size: 1.7em; margin-bottom: 20px;">';
                echo "Nom de la carte : " . $oneCard["carName"] . "<br>" .
                    "Date de création : " . $oneCard["carDate"] . "<br>" .
                    "Crédits : " . $oneCard["carCredits"] . "<br>";
                if ($oneCard["carCondition"] == "N") {
                    echo "Etat : Neuf";
                } else if ($oneCard["carCondition"] == "O") {
                    echo "Etat : Occasion";
                } else if ($oneCard["carCondition"] == "A") {
                    echo "Etat : Abîmé";
                }
                echo "<br>";
                echo "Description : " . "<br>" . $oneCard["carDescription"];
                echo '</div>' . "<br>";
                ?>
                <div class="actions" style="margin-bottom: 20px;">
                    <?php
                    if ($_SESSION['idUser'] == $oneCard['fkUser']) {
                        echo "Actions :";
                    ?>
                        <a href="updateCard.php">
                            <img height="20em" src="./img/edit.png" alt="edit icon">
                        </a>
                        <a href="javascript:confirmDelete()">
                            <img height="20em" src="./img/delete.png" alt="delete icon">
                        </a>
                    <?php
                    } else {
                        echo "Possesseur : " . $card["carUserLogin"] . "<br>";
                    ?>
                        <p>
                            <input type="submit" value="Acheter">
                        </p>
                    <?php
                    }
                    ?>
                </div>

            </div>
        </div>
    </fieldset>
    </div>
    <script src="js/script.js"></script>

</body>

</html>

<?php include("footer.php"); ?>