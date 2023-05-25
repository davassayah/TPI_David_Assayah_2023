<?php

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
                    if (isset($_SESSION['userConnected']) == null) {
                    ?>
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item mt-3">
                                <a class="nav-link" href="index.php">Accueil</a>
                            </li>
                            <li class="nav-item mt-3">
                                <a class="nav-link" href="createAccount.php">Créer un compte</a>
                            </li>
                            <?php
                            if (isset($_SESSION['userConnected']) && $_SESSION['userConnected'] == ('user' or 'admin')) {
                            ?>
                                <li class="nav-item mt-3 text-nowrap">
                                    <a class="nav-link" href="addCard.php">Ajouter une carte</a>
                                </li>
                                <li class="nav-item mt-3 text-nowrap">
                                    <a class="nav-link" href="userProfile.php?idUser=<?php echo $user["idUser"]; ?>"></a>
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
                    if (isset($_SESSION['userConnected']) && $_SESSION['userConnected'] == ('user' or 'admin')) {
                        echo '<form class="nav-admin" action="" method="post">';
                        echo '<span class="nav-item text-white text-nowrap">Bienvenue ' . $_SESSION['useLogin'] . '</span>' . 'Crédits :' . $_SESSION['useCredits'];
                        echo '<button class="btn btn-outline-danger mx-3" type="submit" name="logout">Déconnexion</button>';
                        echo '</form>';
                    } else {
                        echo '<form class="d-flex" action="" method="post">';
                        echo '<input class="form-control me-2 mt-3" type="text" name="login" id="login" placeholder="Login">';
                        echo '<input class="form-control me-2 mt-3" type="password" name="password" id="password" placeholder="Mot de passe">';
                        echo '<button class="btn btn-outline-success mt-3" type="submit">Connexion</button>';
                        echo '</form>';
                    }
                    ?>
            </div>
    </nav>