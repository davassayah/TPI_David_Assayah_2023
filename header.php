<?php

?>

<header>
    <nav class="navbar bg-dark navbar-expand-lg" data-bs-theme="dark">
        <div class="container">
            <a class="navbar-brand">Echange de cartes à collectionner</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <?php
                    if (isset($_SESSION['userConnected']) && $_SESSION['userConnected'] > 1) {
                    ?>
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item mt-3">
                                <a class="nav-link" href="index.php">Accueil</a>
                            </li>
                            <li class="nav-item mt-3">
                                <a class="nav-link" href="createAccount.php">Créer un compte</a>
                            </li>
                            <?php
                            if (isset($_SESSION['userConnected']) && $_SESSION['userConnected'] <= 1) {
                            ?>
                                <li class="nav-item mt-3 text-nowrap">
                                    <a class="nav-link" href="addCard.php">Ajouter une carte</a>
                                </li>
                                <li class="nav-item mt-3 text-nowrap">
                                    <a class="nav-link" href="userProfile.php">Mon profil</a>
                                </li>
                                <li class="nav-item mt-3 text-nowrap">
                                    <a class="nav-link" href="cart.php">Panier</a>
                                </li>
                            <?php
                            }
                            ?>
                        </ul>
                    <?php
                    }
                    ?>
                    <?php
                    if (isset($_SESSION['userConnected']) && $_SESSION['userConnected'] == 1 or 2) {
                        echo '<form class="nav-admin" action="" method="post">';
                        echo '<span class="nav-item text-white text-nowrap">Bienvenue ' . $_SESSION['useFirstName'] . '</span>' . 'Crédits :' . $_SESSION['useCredits'];
                        echo '<button class="btn btn-outline-danger mx-3" type="submit" name="logout">Déconnexion</button>';
                        echo '</form>';
                    } else {
                        echo '<form class="d-flex" action="" method="post">';
                        echo '<input class="form-control me-2 mt-3" type="text" name="user" id="user" placeholder="Login">';
                        echo '<input class="form-control me-2 mt-3" type="password" name="password" id="password" placeholder="Mot de passe">';
                        echo '<button class="btn btn-outline-success mt-3" type="submit">Connexion</button>';
                        echo '</form>';
                    }
                    ?>
            </div>
    </nav>