<?php

/**
 * ETML
 * Auteur: David Assayah
 * Date: 22.05.2023
 * Description: Fichier permettant de se connecter à la db et contenant toutes les fonctions utilisées
 */

 class Database
{
    
    private static $instance = null;
    private $connector;

    private function __construct()
    {
        include(__DIR__ . "/utils.php");
        $configs = include(__DIR__ . "/../config.php");
        try {
            $this->connector = new PDO(
                $configs['dns'],
                $configs['user'],
                getPassword()
            );
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

        //Fonction permettant d'exécuter une requête de type simple
        private function querySimpleExecute($query)
        {
            return $this->connector->query($query);
        }
    
        //Fonction permettant de préparer, de binder et d'exécuter une requête (select avec where ou insert, update et delete)
        private function queryPrepareExecute($query, $binds)
        {
    
            $req = $this->connector->prepare($query);
            foreach ($binds as $bind => $value) {
                $req->bindValue($bind, $value);
            };
            $req->execute();
            return $req;
        }
    
    
        //Fonction permettant de traiter les données pour les retourner par exemple en tableau associatif (avec PDO::FETCH_ASSOC)
        private function formatData($req)
        {
            return $req->fetchALL(PDO::FETCH_ASSOC);
        }
    
        //Fonction permettant de vider le jeu d'enregistrement
        private function unsetData($req)
        {
            unset($req->data);
        }

        

}