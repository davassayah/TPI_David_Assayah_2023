<?php

/**
 * ETML
 * Auteur: David Assayah
 * Date: 24.05.2023
 * Description: Page permettant de modifier les informations d'une carte
 */

include("header.php");
include_once(__DIR__ . "/validateUpdateCardForm.php");
include("uploadImages/updateImages.php");

if (!isset($_SESSION['userConnected']) || $_SESSION['userConnected'] != ('user' or 'admin')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    require_once(__DIR__ . "/403.php");
    exit;
}

$oneCard = $db->getOneCard($_GET["idCard"]);

$errors  = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $imageData = updateImages($_FILES, $card);
    $_POST["imgPath"] = $imageData["imgPath"];
    $result = validateUpdateCardForm($db,$card);
    $errors = $result["errors"];
    $cardData = $result["cardData"];

    if (count($errors) > 0) {
        // Si le compte des erreurs est supérieur à 0, on affiche les erreurs
        echo "Merci de vérifier que tous les champs sont bien remplis correctement et que l'extension du fichier est jpg/png";
    } else {
        //Si le formulaire a été envoyé avec succès, alors un nouvel enseignant est créé 
        if ($_POST) {
            // si le formulaire a été envoyé, alors on met à jour l'enseignant
            if ($imageData["fileNameImg"] !== null) {
                // Si une image a été sélectionnée, on la déplace et on met à jour l'enseignant avec la nouvelle image
                move_uploaded_file($imageData["fileTmpNameImg"], $imageData["filePath"]);
                $db->updateCardById($_GET["idCard"],$_POST);
            } else {
                // Sinon, on met à jour l'enseignant sans changer l'image
                $db->updateCardById($_GET["idCard"], $_POST);
            }
            // On redirige vers la page d'accueil
            header('Location: index.php');
            exit();
        } else {
            // Si le formulaire n'a pas été envoyé, on affiche un message d'erreur
            echo "Merci de remplir le formulaire.";
        }
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
    <div class="container">
        <fieldset class="mb-5 mt-4">
            <div class="user-body">
                <form action="#" method="post" id="form" enctype="multipart/form-data">
                    <h3>Modifier une carte</h3>
                    </p>
                    <p>
                        <label for="name">Nom :</label>
                        <input type="text" name="name" id="name" value="<?php echo $oneCard['carName']; ?>">
                        <span id="show-error">
                            <?= array_key_exists("name", $errors) && $errors["name"] ? '<p style="color:red;">' . $errors["name"] . '</p>' : '' ?>
                        </span>
                    </p>
                    </p>
                    <p>
                        <label for="date">Année de création :</label>
                        <input type="text" name="date" id="date" value="<?php echo $oneCard['carDate'] ?>">
                        <span id="show-error">
                            <?= array_key_exists("date", $errors) && $errors["date"] ? '<p style="color:red;">' . $errors["date"] . '</p>' : '' ?>
                        </span>
                    </p>
                    </p>
                    <p>
                        <label for="credits">Crédits :</label>
                        <input type="text" name="credits" id="credits" value="<?php echo $oneCard['carCredits'] ?>">
                        <span id="show-error">
                            <?= array_key_exists("credits", $errors) && $errors["credits"] ? '<p style="color:red;">' . $errors["credits"] . '</p>' : '' ?>
                        </span>
                    </p>
                    </p>
                    <p>
                        <!--Condition permettant de sélectionner l'état de la carte déjà renseigné-->
                        <input type="radio" id="new" name="condition" value="N" <?php if ($oneCard['carCondition'] == 'N') { ?>checked<?php } ?>>
                        <label for="condition1">Neuf</label>
                        <input type="radio" id="secondHand" name="condition" value="O" <?php if ($oneCard['carCondition'] == 'O') { ?>checked<?php } ?>>
                        <label for="condition2">Occasion</label>
                        <input type="radio" id="damaged" name="condition" value="A" <?php if ($oneCard['carCondition'] == 'A') { ?>checked<?php } ?>>
                        <label for="condition3">Abîmé</label>
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
                                if ($collection["idCollection"] === $oneCard["fkCollection"]) {

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
                        <textarea name="description" id="description"><?php echo $oneCard['carDescription'] ?></textarea>
                    </p>
                    <span id="show-error">
                        <?= array_key_exists("description", $errors) && $errors["description"] ? '<p style="color:red;">' . $errors["description"] . '</p>' : '' ?>
                    </span>
                    <p>
                    <div>
                        <img src=<?php echo $oneCard["carPhoto"] ?>>
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