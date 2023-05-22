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
    <fieldset class="mb-3 mt-5">
        <div class="container">
            <div class="user-body">
                <h3>Informations de la carte : </h3> <?php
                echo "Nom de la carte : " . $OneCard["carName"] ."<br>" . 
                "Date de création : " . $OneCard["carDate"] . "<br>" . 
                "Crédits : " . $OneCard["carCredits"] . "<br>";
                if ($OneCard["carCondition"] == "N") {
                    echo "Etat :" . '<img style="margin-left: 1vw;" height="20em" src="./img/neuf.png" alt="neuf symbole">';
                } else if ($OneCard["carCondition"] == "O") {
                    echo  "Etat :" . '<img style="margin-left: 1vw;" height="20em" src="./img/occasion.png" alt="occasion symbole">';
                } else if ($OneCard["carCondition"] == "A") {
                    echo "Etat :" . '<img style="margin-left: 1vw;" height="20em" src="./img/abîmé.png" alt="abîmé symbole">';
                }
                "Description : " . $OneCard["carDescription"] ?>
                <div class="actions">
                    <!--Si l'utilisateur regarde une de ses propres cartes il peut la modifier ou la supprimer, sinon le nom du possesseur et un bouton d'achat s'affiche à la place -->
                    <?php if ($_SESSION['userConnected'] == 1) {
                        echo " Actions : " ?>
                        <a href="updateCard.php">
                            <img height="20em" src="./img/edit.png" alt="edit icon"></a>
                        <a href="javascript:confirmDelete()">
                            <img height="20em" src="./img/delete.png" alt="delete icon"> </a>
                    <?php } ?>
                    <div>
                        <img height="300em" src="<?php echo $OneCard["carPhoto"] ?>">
                    </div>
                </div>
            </div>
    </fieldset>
    </div>
    <script src="js/script.js"></script>

</body>

</html>

<?php include("footer.php"); ?>