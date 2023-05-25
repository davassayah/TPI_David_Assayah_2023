<?php

if (!isset($_SESSION['userConnected']) || $_SESSION['userConnected'] != 'user' or 'admin') {
    header('HTTP/1.0 403 Forbidden', true, 403);
    require_once(__DIR__ . "/403.php");
    exit;
}

/**
 * Retourne le mot de passe de l'utilisateur
 * 
 * stocker les secrets dans un fichier json
 */
function getPassword()
{
    //read json file from url in php
    $readJSONFile = file_get_contents(__DIR__ . "/../secrets.json");

    //convert json to array in php
    $array = json_decode($readJSONFile, TRUE);

    return $array["password"];
}
