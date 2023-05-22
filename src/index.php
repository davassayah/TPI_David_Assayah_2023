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

    <title>Echange de cartes</title>

</head>

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
                <div id="filter-section" class="col-2 d-none">
                    <label for="section_id" class="form-label">Collection</label>
                    <select name="section_id" id="section_id" class="form-select" aria-label="Default select example">
                        <option value="">Collection</option>
                        <?php foreach ($collections as $collection) { ?>
                            <option value="<?php echo $collection["idCollection"] ?>"><?php echo $collection["colName"] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div id="filter-condition" class="col-2 d-none">
                    <label for="condition" class="form-label">Etat</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="neuf" value="N" name="conditions[]">
                        <label class="form-check-label" for="male">Neuf</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="used" value="U" name="conditions[]">
                        <label class="form-check-label" for="female">Occasion</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="damaged" value="D" name="conditions[]">
                        <label class="form-check-label" for="other">Abîmé</label>
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
                                Etat
                            </th>
                            <th class="th-sm">
                                Possesseur
                            </th>
                            <th class="th-sm">
                                Crédits
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
                                <td><?php echo $card["carCondition"] ?></td>
                                <td><?php echo $card["carCollectionName"] ?></td>
                                <td class="containerOptions">
                                    <!--Affiche différentes fonctionnalités selon que l'utilisateur soit connecté en tant qu'utilisateur ou en tant qu'admin-->
                                    <?php if (isset($_SESSION['userConnected']) && $_SESSION['userConnected'] >= 1) { ?>
                                        <?php if (isset($_SESSION['userConnected']) && $_SESSION['userConnected'] == 1) { ?>
                                            <a class="link-light" href="updateCard.php?idCard=<?php echo $card["idCard"]; ?>">
                                                <img height="20em" src="./img/modify.png" alt="edit">
                                            </a>
                                            <a class="link-light" href="javascript:confirmDelete(<?php echo $card["idCard"] ?>)">
                                                <img height="20em" src="./img/delete.png" alt="delete">
                                            </a>
                                        <?php } ?>
                                        <a class="link-light" href="cardDetails.php?idCard=<?php echo $card["idCard"] ?>">
                                            <img height="20em" src="./img/detail.png" alt="detail">
                                        </a>
                                        <!-- <a class="link-light" href="Cart.php?idCard=<?php echo $card["idCard"] ?>">
                                            <img height="20em" src="./img/buy.png" alt="buy">
                                        </a> -->
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
                    info: "_TOTAL_ résultats trouvés",
                    paginate: {
                        next: "Suivant",
                        previous: "Précédent"
                    }
                }
            });

            // Afficher/Cacher les filtres en fonction du bouton "Plus de filtres"
            $('#more-filters-btn').click(function() {
                $('#filter-section').toggleClass('d-none');
                $('#filter-gender').toggleClass('d-none');
            });
        });
    </script>

</body>

</html>