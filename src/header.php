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
        $_SESSION['connexionError'] = 'Erreur de connexion';
    } else if ($user != null) {
        if (isset($_SESSION['connexionError'])) {
            unset($_SESSION['connexionError']);
        }
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
                <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll">
                    <?php
                    if (!isset($_SESSION['userConnected'])) {
                        // Affichage pour les utilisateurs non connectés
                    ?>
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="index.php">Accueil</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="createAccount.php">Créer un compte</a>
                            </li>
                        </ul>
                        <?php
                    } else {
                        // Affichage pour les utilisateurs connectés
                        if ($_SESSION['userConnected'] == 'user' || $_SESSION['userConnected'] == 'admin') {
                        ?>
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="index.php">Accueil</a>
                                </li>
                                <li class="nav-item text-nowrap">
                                    <a class="nav-link" href="addCard.php">Ajouter une carte</a>
                                </li>
                                <li class="nav-item text-nowrap">
                                    <a class="nav-link" href="userProfile.php?idUser=<?php echo $_SESSION["idUser"]; ?>">Profil</a>
                                </li>
                                <li class="nav-item text-nowrap">
                                    <a class="nav-link" href="cart.php">Panier</a>
                                </li>
                            <?php } ?>
                            </ul>
                        <?php
                    }
                        ?>
                </ul>
                <?php if (isset($_SESSION['userConnected']) and $_SESSION['userConnected'] == ('user' or 'admin')) { ?>
                    <form class="hstack gap-3 mb-0" action="" method="post">
                        <div class="me-2 text-white">
                            Bienvenue <?php echo $_SESSION['useLogin'] ?><br>
                            Crédits : <?php echo intval($_SESSION['useCredits']) ?>
                        </div>
                        <button class="btn btn-outline-danger mx-1" type="submit" name="logout">
                            Déconnexion
                        </button>
                    </form>
                <?php } else { ?>
                    <form class="hstack gap-3 mb-0" action="" method="post">
                        <input class="form-control" type="text" name="login" id="login" placeholder="Login">
                        <input class="form-control" type="password" name="password" id="password" placeholder="Mot de passe">
                        <button class="btn btn-outline-success" type="submit">
                            Connexion
                        </button>
                    </form>
                <?php } ?>
            </div>
        </div>
    </nav>
</header>