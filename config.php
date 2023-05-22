<?php

/**
 * ETML
 * Auteur: David Assayah
 * Date: 28.04.2023
 * Description: Fichier permettant la gestion de la configuration de la base de données
 */

$configs = array(
    'db'      => "mysql",
    'host'    => "localhost",
    'port'    => "6033",
    'dbname'  => "EchangeDeCartesACollectionner",
    'charset' => "utf8",
    'user'    => 'root',
);

$configs["dns"] = $configs["db"] . ":host=" . $configs["host"] . ";dbname=" . $configs["dbname"] . ";charset=" . $configs["charset"] . ";port=" . $configs["port"];

return $configs;
