<?php

/**
 * ETML
 * Auteur: David Assayah
 * Date: 24.05.2023
 * Description: Page permettant de modifier les informations d'une carte
 */

include("header.php");

$db->updateCardById($_GET["idCard"], $_POST);

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
    <div class="container">
        <fieldset class="mb-5 mt-4">
            <div class="user-body">
                <form action="#" method="post" id="form" enctype="multipart/form-data">
                    <h3>Modifier une carte</h3>
                    </p>
                    <p>
                        <label for="name">Nom :</label>
                        <input type="text" name="name" id="name" value="<?php echo $card['carName'] ?>">
                        <span id="show-error">
                            <?= array_key_exists("name", $errors) && $errors["name"] ? '<p style="color:red;">' . $errors["name"] . '</p>' : '' ?>
                        </span>
                    </p>
                    </p>
                    <p>
                        <label for="date">Année de création :</label>
                        <input type="text" name="date" id="date" value="<?php echo $card['carDate'] ?>">
                        <span id="show-error">
                            <?= array_key_exists("date", $errors) && $errors["date"] ? '<p style="color:red;">' . $errors["date"] . '</p>' : '' ?>
                        </span>
                    </p>
                    </p>
                    <p>
                        <label for="credits">Crédits :</label>
                        <input type="text" name="credits" id="credits" value="<?php echo $card['carCredits'] ?>">
                        <span id="show-error">
                            <?= array_key_exists("credits", $errors) && $errors["credits"] ? '<p style="color:red;">' . $errors["credits"] . '</p>' : '' ?>
                        </span>
                    </p>
                    </p>
                    <p>
                        <!--Condition permettant de sélectionner l'état de la carte déjà renseigné-->
                        <input type="radio" id="new" name="new" value="N" <?php if ($card['carCondition'] == 'N') { ?>checked<?php } ?>>
                        <label for="genre1">Neuf</label>
                        <input type="radio" id="secondHand" name="secondHand" value="O" <?php if ($card['carCondition'] == 'O') { ?>checked<?php } ?>>
                        <label for="genre2">Occasion</label>
                        <input type="radio" id="damaged" name="damaged" value="A" <?php if ($card['carCondition'] == 'A') { ?>checked<?php } ?>>
                        <label for="genre3">Abîmé</label>
                        <span id="show-error">
                            <?= array_key_exists("condition", $errors) && $errors["condition"] ? '<p style="color:red;">' . $errors["condition"] . '</p>' : '' ?>
                        </span>
                    </p>
                    <p>
                        <label style="display: none" for="section"></label>
                        <select name="collection" id="collection">
                            <option value="">Collection</option>
                            <!--Condition permettant de sélectionner la collection de la carte déjà renseignée-->
                            <?php
                            $html = "";
                            foreach ($collections as $collection) {

                                $html .= "<option value='" . $collection["idCollection"]  . "' ";
                                if ($collection["idCollection"] === $card["fkCollection"]) {

                                    $html .= " selected ";
                                }
                                $html .= " >" . ($collection["colName"]) . "</option>";
                            }
                            echo $html;
                            ?>
                        </select>
                    </p>
                    <span id="show-error">
                        <?= array_key_exists("collection", $errors) && $errors["collection"] ? '<p style="color:red;">' . $errors["collection"] . '</p>' : '' ?>
                    </span>
                    <p>
                        <label for="description">Description :</label>
                        <textarea name="description" id="description"><?php echo $card['carDescription'] ?></textarea>
                    </p>
                    <span id="show-error">
                        <?= array_key_exists("description", $errors) && $errors["description"] ? '<p style="color:red;">' . $errors["description"] . '</p>' : '' ?>
                    </span>
                    <p>
                    <div>
                        <img src=<?php echo $card["carPhoto"] ?>>
                    </div>
                    <label for="downloadImg">Photo de la carte (format jpg) :</label>
                    <br>
                    <input type="file" name="downloadImg" id="downloadImg" />
                    <br>
                    <a href="https://convertio.co/fr/convertisseur-jpg/" target="_blank">Convertissez votre fichier au format jpg en cliquant ici</a>
                    <span id="show-error">
                        <?= array_key_exists("downloadImg", $errors) && $errors["downloadImg"] ? '<p style="color:red;">' . $errors["downloadImg"] . '</p>' : '' ?>
                    </span>
                    </p>
                    <p>
                        <input type="submit" value="Modifier">
                    </p>
                </form>
            </div>
    </div>
</body>

</html>

<?php include("footer.php"); ?>