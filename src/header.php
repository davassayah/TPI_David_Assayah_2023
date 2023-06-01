<?php

/**
 * ETML
 * Auteur: David Assayah
 * Date: 23.05.2023
 * Description: Page permettant la navigation du site, la connexion à la db ainsi que la gestion des variables de session.
 */

session_start();

require_once 'database.php';
$db = Database::getInstance();



if (isset($_POST['login']) && isset($_POST['password'])) {
    $user = $db->CheckAuth($_POST['login'], $_POST['password']);
    if ($user == null) {
        echo "erreur de connexion";
    } else if ($user != null) {
        //echo "vous êtes connecté";
        $_SESSION['userConnected'] = $user['useRole'];
        $_SESSION['idUser'] = $user['idUser'];
        $_SESSION['useLogin'] = $db->getOneUser($_SESSION['idUser'])['useLogin'];
        $_SESSION['useCredits'] = $db->getOneUser($_SESSION['idUser'])['useCredits'];
        // Si le panier n'existe pas en session, on va en créer un
        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = [];
        }
    }
}

// Déconnexion de l'utilisateur et redirection vers la page d'accueil
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}

?>

<header>
    <nav class="navbar bg-dark navbar-expand-lg" data-bs-theme="dark">
        <div class="container">
            <a class="navbar-brand">Echange de cartes à collectionner</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <?php
                    if (!isset($_SESSION['userConnected'])) {
                        // Affichage pour les utilisateurs non connectés
                    ?>
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item mt-2">
                                <a class="nav-link" href="index.php">Accueil</a>
                            </li>
                            <li class="nav-item mt-2">
                                <a class="nav-link" href="createAccount.php">Créer un compte</a>
                            </li>
                        </ul>
                        <?php
                    } else {
                        // Affichage pour les utilisateurs connectés
                        if ($_SESSION['userConnected'] == 'user' || $_SESSION['userConnected'] == 'admin') {
                        ?>
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item mt-2">
                                    <a class="nav-link" href="index.php">Accueil</a>
                                </li>
                                <?php if ($_SESSION['userConnected'] == 'user' || $_SESSION['userConnected'] == 'admin') { ?>
                                    <li class="nav-item mt-2 text-nowrap">
                                        <a class="nav-link" href="addCard.php">Ajouter une carte</a>
                                    </li>
                                    <li class="nav-item mt-2 text-nowrap">
                                        <a class="nav-link" href="userProfile.php?idUser=<?php echo $_SESSION["idUser"]; ?>">Profil</a>
                                    </li>
                                    <li class="nav-item mt-2 text-nowrap">
                                        <a class="nav-link" href="cart.php">Panier</a>
                                    </li>
                                <?php } ?>
                            </ul>
                    <?php
                        }
                    }
                    ?>
                </ul>
                <?php
                if (isset($_SESSION['userConnected']) && $_SESSION['userConnected'] == ('user' or 'admin')) {
                    echo '<form class="nav-admin" action="" method="post">';
                    echo '<span class="nav-item text-white text-wrap">Bienvenue ' . $_SESSION['useLogin']  . "<br>" . ' Crédits : ' . intval($_SESSION['useCredits']) . '</span>';

                    echo '<button class="btn btn-outline-danger mx-1" type="submit" name="logout">Déconnexion</button>';
                    echo '</form>';
                } else {
                    echo '<form class="nav-forreign" action="" method="post">';
                    echo '<input class="form-control me-2 mt-3" type="text" name="login" id="login" placeholder="Login">';
                    echo '<input class="form-control me-2 mt-3" type="password" name="password" id="password" placeholder="Mot de passe">';
                    echo '<button class="btn btn-outline-success mt-3" type="submit">Connexion</button>';
                    echo '</form>';
                }
                ?>
            </div>
    </nav>