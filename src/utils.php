<?php

/**
 * ETML
 * Auteur: David Assayah
 * Date: 23.05.2023
 * Description: Page permettant la récupération du mot de passe via le fichier secrets.json
 */

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
