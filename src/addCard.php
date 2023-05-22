<?php

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
                    <br>
                    <p style="color:red;">
                        <?php if (isset($errorOrValidationMessage)) {
                            echo $errorOrValidationMessage;
                        } ?>
                    </p>
                    <p>
                        <br><br>
                        <label for="name">Nom :</label>
                        <input type="text" name="name" id="name" value=<?php if (isset($name)) echo $name ?>>
                        <span id="show-error">
                            <?= array_key_exists("name", $errors) && $errors["name"] ? '<p style="color:red;">' . $errors["name"] . '</p>' : '' ?>
                        </span>
                    </p>
                    <p>
                        <label for="date">Date de création :</label>
                        <input type="text" name="date" id="date" value=<?php if (isset($date)) echo $date ?>>
                        <span id="show-error">
                            <?= array_key_exists("date", $errors) && $errors["date"] ? '<p style="color:red;">' . $errors["date"] . '</p>' : '' ?>
                        </span>
                    </p>
                    </p>
                    <p>
                    <p>
                        <label for="credits">Crédits :</label>
                        <input type="text" name="credits" id="credits" value=<?php if (isset($credits)) echo $credits ?>>
                    </p>
                    <span id="show-error">
                        <?= array_key_exists("credits", $errors) && $errors["credits"] ? '<p style="color:red;">' . $errors["credits"] . '</p>' : '' ?>
                    </span>
                    </p>
                    <p>
                        <input type="radio" id="new" name="new" value="N" checked>
                        <label for="neuf">Neuf</label>
                        <input type="radio" id="secondHand" name="secondHand" value="O">
                        <label for="occasion">Occasion</label>
                        <input type="radio" id="damaged" name="damaged" value="A">
                        <label for="genre3">Abîmé</label>
                        <span id="show-error">
                            <?= array_key_exists("condition", $errors) && $errors["condition"] ? '<p style="color:red;">' . $errors["condition"] . '</p>' : '' ?>
                        </span>
                    </p>
                    <p>
                        <label style="display: none" for="collection"></label>
                        <select name="collection" id="collection">
                            <option value="">Collection</option>
                            <?php
                            $html = "";
                            foreach ($collections as $collection) {

                                $html .= "<option value='" . $collection["idCollection"]  . "'>"  . ($collection["colName"]) . "</option>";
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
                        <textarea name="description" id="description"></textarea>
                    </p>
                    <p>
                        <label for="downloadImg">Photo de la carte (format jpg) :</label>
                        <br>
                        <input type="file" name="downloadImg" id="downloadImg" />
                        <br>
                        <a href="https://convertio.co/fr/convertisseur-jpg/">Convertissez votre fichier au format jpg en cliquant ici</a>
                        <span id="show-error">
                            <?= array_key_exists("downloadImg", $errors) && $errors["downloadImg"] ? '<p style="color:red;">' . $errors["downloadImg"] . '</p>' : '' ?>
                        </span>
                    </p>
                    <p>
                        <input type="submit" value="Ajouter">
                    </p>
                </form>
    </fieldset>
    </div>
    </div>
</body>

</html>

<?php include("footer.php"); ?>