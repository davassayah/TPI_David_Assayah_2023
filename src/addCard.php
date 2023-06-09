<?php

/**
 * ETML
 * Auteur: David Assayah
 * Date: 23.05.2023
 * Description: Page permettant d'ajouter une carte à la BD
 */

include("header.php");
include("uploadImages/addImages.php");
include_once(__DIR__ . "/validateAddCardForm.php");

if (!isset($_SESSION['userConnected']) || $_SESSION['userConnected'] != ('user' or 'admin')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    require_once(__DIR__ . "/403.php");
    exit;
}

$collections = $db->getAllCollections();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $result = validateAddCardForm($db);
    $errors = $result["errors"];
    $cardData = $result["cardData"];
    $imageData = addImages($_FILES, $db);

    // Si aucune erreur de validation 
    // Cela signifie que les données sont propres et validées
    // Nous pouvons insérer les données en BD
    if (count($errors) === 0) {

        move_uploaded_file($imageData['fileTmpNameImg'], $imageData['uploadPathImg']);
        $cards = $db->addCard($cardData, $imageData, $_SESSION['idUser']);

        echo '<script>';
        echo 'window.onload = function() {';
        echo '    showSuccessAddCardMessage();';
        echo '}';
        echo '</script>';

    } else {
        if ($_POST) {
            $errorMessage= "Merci de bien remplir tous les champs en respectant les contraintes de validation";
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
    <fieldset class="help mb-3 mt-5">
        <div class="container">
            <div class="user-body">
                <form action="#" method="post" id="form" enctype="multipart/form-data">
                    <h3>Ajout d'une carte</h3>
                    <p style="color:red;">
                        <?php if (isset($errorMessage)) {
                            echo $errorMessage;
                        } ?>
                    </p>
                    <p>
                        <label for="name">Nom :</label>
                        <input type="text" name="name" id="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    </p>
                    <span id="show-error">
                        <?php echo (array_key_exists("name", $errors) && $errors["name"]) ? '<p style="color:red;">' . $errors["name"] . '</p>' : ''; ?>
                    </span>
                    <p>
                        <label for="date">Date de création :</label>
                        <input type="text" name="date" id="date" value="<?php echo isset($_POST['date']) ? htmlspecialchars($_POST['date']) : ''; ?>">
                        <span id="show-error">
                            <?php echo (array_key_exists("date", $errors) && $errors["date"]) ? '<p style="color:red;">' . $errors["date"] . '</p>' : ''; ?>
                        </span>
                    </p>
                    </p>
                    <p>
                    <p>
                        <label for="credits">Crédits :</label>
                        <input type="text" name="credits" id="credits" value="<?php echo isset($_POST['credits']) ? htmlspecialchars($_POST['credits']) : ''; ?>">
                    </p>
                    <span id="show-error">
                        <?php echo (array_key_exists("credits", $errors) && $errors["credits"]) ? '<p style="color:red;">' . $errors["credits"] . '</p>' : ''; ?>
                    </span>
                    <p>
                        <input type="radio" id="new" name="condition" value="N" <?php echo isset($_POST['condition']) && $_POST['condition'] === 'N' ? 'checked' : ''; ?>>
                        <label for="condition1">Neuf</label>
                        <input type="radio" id="secondHand" name="condition" value="O" <?php echo isset($_POST['condition']) && $_POST['condition'] === 'O' ? 'checked' : ''; ?>>
                        <label for="condition2">Occasion</label>
                        <input type="radio" id="damaged" name="condition" value="A" <?php echo isset($_POST['condition']) && $_POST['condition'] === 'A' ? 'checked' : ''; ?>>
                        <label for="condition3">Abîmé</label>
                        <span id="show-error">
                            <?= array_key_exists("condition", $errors) && $errors["condition"] ? '<p style="color:red;">' . $errors["condition"] . '</p>' : '' ?>
                        </span>
                    </p>
                    <label for="description">Description :</label>
                    <textarea name="description" id="description"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    </p>
                    <span id="show-error">
                        <?= array_key_exists("description", $errors) && $errors["description"] ? '<p style="color:red;">' . $errors["description"] . '</p>' : '' ?>
                    </span>
                    <p>
                        <label for="downloadImg">Photo de la carte (format jpg) :</label>
                        <br>
                        <input type="file" name="downloadImg" id="downloadImg" />
                        <br>
                        <a href="https://convertio.co/fr/convertisseur-jpg/">
                            Convertissez votre fichier au format jpg en cliquant ici</a>
                        <span id="show-error">
                            <?= array_key_exists("downloadImg", $errors) && $errors["downloadImg"] ?
                             '<p style="color:red;">' . $errors["downloadImg"] . '</p>' : '' ?>
                        </span>
                    </p>
                    <p>
                        <label style="display: none" for="collection"></label>
                        <select name="collection" id="collection">
                            <option value="">Collection</option>
                            <?php
                            $html = "";
                            foreach ($collections as $collection) {
                                $selected = (isset($_POST['collection']) && $_POST['collection'] == $collection["idCollection"]) ? "selected" : "";
                                $html .= "<option value='" . $collection["idCollection"]  . "' " . $selected . ">"  . ($collection["colName"]) . "</option>";
                            }
                            echo $html;
                            ?>
                        </select>
                    </p>
                    <span id="show-error">
                        <?= array_key_exists("collection", $errors) && $errors["collection"] ? '<p style="color:red;">' . $errors["collection"] . '</p>' : '' ?>
                    </span>

                    <p>
                        <input type="submit" value="Ajouter">
                    </p>
                </form>
    </fieldset>
    </div>
    </div>
    <script src="./js/script.js"></script>
</body>

</html>

<?php include("footer.php"); ?>