<?php

/**
 * ETML
 * Auteur: David Assayah
 * Date: 24.05.2023
 * Description: Page permettant d'ajouter un utilisateur à la db
 */

include("header.php");
include_once(__DIR__ . "/validateAddUserForm.php");

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $result = validateAddUserForm($db);
    $errors = $result["errors"];
    $userData = $result["userData"];

    // Si aucune erreur de validation 
    // Cela signifie que les données sont propres et validées
    // Nous pouvons insérer les données en BD
    if (count($errors) === 0) {

        $teachers = $db->addUser($_POST);

        $errorOrValidationMessage = "Votre compte a bien été crée";
    } else {
        if ($_POST) {
            $errorOrValidationMessage = "Merci de bien remplir tous les champs marqués comme obligatoires";
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
                    <h3>Créer un compte</h3>
                    <br>
                    <p style="color:red;">
                        <?php if (isset($errorOrValidationMessage)) {
                            echo $errorOrValidationMessage;
                        } ?>
                    </p>
                    <p>
                        <label for="login">Login :</label>
                        <input type="text" name="login" id="login" value=<?php if (isset($login)) echo $login ?>>
                        <span id="show-error">
                            <?= array_key_exists("login", $errors) && $errors["login"] ? '<p style="color:red;">' . $errors["login"] . '</p>' : '' ?>
                        </span>
                    </p>
                    <p>
                        <label for="email">Email :</label>
                        <input type="text" name="email" id="email" value=<?php if (isset($email)) echo $email ?>>
                        <span id="show-error">
                            <?= array_key_exists("email", $errors) && $errors["email"] ? '<p style="color:red;">' . $errors["email"] . '</p>' : '' ?>
                        </span>
                    </p>
                    <p>
                        <label for="firstName">Prénom :</label>
                        <input type="text" name="firstName" id="firstName" value=<?php if (isset($firstname)) echo $firstname ?>>
                        <span id="show-error">
                            <?= array_key_exists("firstName", $errors) && $errors["firstName"] ? '<p style="color:red;">' . $errors["firstName"] . '</p>' : '' ?>
                        </span>
                    </p>
                    <p>
                        <label for="lastName">Nom :</label>
                        <input type="text" name="lastName" id="lastName" value=<?php if (isset($lastName)) echo $lastName ?>>
                        <span id="show-error">
                            <?= array_key_exists("lastName", $errors) && $errors["lastName"] ? '<p style="color:red;">' . $errors["lastName"] . '</p>' : '' ?>
                        </span>
                    </p>
                    <p>
                        <label for="locality">Localité :</label>
                        <input type="text" name="locality" id="locality" value=<?php if (isset($locality)) echo $locality ?>>
                    </p>
                    <span id="show-error">
                        <?= array_key_exists("locality", $errors) && $errors["locality"] ? '<p style="color:red;">' . $errors["locality"] . '</p>' : '' ?>
                    </span>
                    </p>
                    <p>
                        <label for="postalCode">Code Postal :</label>
                        <input type="text" name="postalCode" id="postalCode" value=<?php if (isset($postalCode)) echo $postalCode ?>>
                    </p>
                    <span id="show-error">
                        <?= array_key_exists("postalCode", $errors) && $errors["postalCode"] ? '<p style="color:red;">' . $errors["postalCode"] . '</p>' : '' ?>
                    </span>
                    </p>
                    <p>
                        <label for="streetName">Nom de la rue :</label>
                        <input type="text" name="streetName" id="streetName" value=<?php if (isset($streetName)) echo $streetName ?>>
                    </p>
                    <span id="show-error">
                        <?= array_key_exists("streetName", $errors) && $errors["streetName"] ? '<p style="color:red;">' . $errors["streetName"] . '</p>' : '' ?>
                    </span>
                    </p>
                    <p>
                        <label for="streetNumber">Numéro : </label>
                        <input type="text" name="streetNumber" id="streetNumber" value=<?php if (isset($streetNumber)) echo $streetNumber ?>>
                    </p>
                    <span id="show-error">
                        <?= array_key_exists("streetNumber", $errors) && $errors["streetNumber"] ? '<p style="color:red;">' . $errors["streetNumber"] . '</p>' : '' ?>
                    </span>
                    </p>
                    <p>
                        <label for="password">Mot de passe :</label>
                        <input type="password" name="password" id="password" value=<?php if (isset($password)) echo $password ?>>
                    </p>
                    <span id="show-error">
                        <?= array_key_exists("password", $errors) && $errors["password"] ? '<p style="color:red;">' . $errors["password"] . '</p>' : '' ?>
                    </span>
                    </p>
                    <p>
                        <input type="submit" value="Créer un compte">
                    </p>
                </form>
    </fieldset>
    </div>
    </div>
</body>

</html>

<?php include("footer.php"); ?>