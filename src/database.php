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

    /**
     * Fonction permettant d'authentifier l'utilisateur via son login ou son email
     * @param $user string | login de l'utilisateur
     * @param $password string | mot de passe de l'utilisateur
     */
    public function CheckAuth($useLogin, $password)
    {
        $query = "
                SELECT * 
                FROM t_user 
                WHERE useLogin = :useLogin
                OR useEmail = :useLogin
            ";

        $replacements = ['useLogin' => $useLogin];
        $req = $this->queryPrepareExecute($query, $replacements);
        $user = $this->formatData($req)[0];

        if (password_verify($password, $user['usePassword'])) {
            return $user;
        } else {
            echo 'Erreur de connexion';
        }
    }
 
     /**
     * Fonction permettant de créer un nouvel utilisateur
     * @param $user array | contient tous les attributs d'un utilisateur à créer
     */
    public function addUser($user)
    {
        $query = "
                INSERT INTO t_user (useLogin, useEmail, useFirstName, useLastName, useLocality, usePostalCode, useStreetName, useStreetNumber, usePassword, useCredits, useRole) 
                VALUES (:login, :email, :firstName, :lastName, :locality, :postalCode, :streetName, :streetNumber, :password, :credits, :role);
            ";

        $replacements = [
            'login' => $user['login'],
            'email' => $user['email'],
            'firstName' => $user['firstName'],
            'lastName' => $user['lastName'],
            'locality' => $user['locality'],
            'postalCode' => $user['postalCode'],
            'streetName' => $user['streetName'],
            'streetNumber' => $user['streetNumber'],
            'password' => password_hash($user['password'], PASSWORD_BCRYPT),
            'credits' => $user ['credits'],
            'role' => $user ['role'],
        ];

        $response = $this->queryPrepareExecute($query, $replacements);
    }

       //Recupère la liste des informations pour un utilisateur
       public function getOneUser($id)
       {
           //avoir la requête sql pour un utilisateur (utilisation de l'id)
           $query = "SELECT * FROM t_user WHERE idUser = :id";
           //appeler la méthode pour executer la requête
           $bind = array('id' => $id);
           $req = $this->queryPrepareExecute($query, $bind);
           //appeler la méthode pour avoir le résultat sous forme de tableau
           $OneUser = $this->formatData($req);
           //retourne l'utilisateur
           return $OneUser[0];
       }

       /**
     * Fonction permettant de créer une nouvelle carte
     * @param $card array | contient tous les attributs d'une carte à créer
     * @param $imgData array | contient tous les attributs de l'image à uploader
     */
    public function addCard($card, $imgData)
    {
        $query = "
                INSERT INTO t_card (carName, carDate, carCredits, carCondition, carDescription, carIsAvailable, carPhoto, fkUser, fkCollection) 
                VALUES (:name, :date, :credits, :condition, :description, :isAvailable, :photo, :fkUser, fkCollection);
            ";

        $replacements = [
            'name' => $card['name'],
            'date' => $card['date'],
            'credits' => $card['credits'],
            'condition' => $card['condition'],
            'description' => $card['description'],
            'isAvailable' => $card['isAvailable'],
            'photo' => $card['uploadDirectoryImg'] . $imgData['fileNameImg'],
            'fkUser' => $card['user'],
            'fkCollection' => $card['collection']
        ];

        $response = $this->queryPrepareExecute($query, $replacements);
    }

    //Fonction permettant de récupérer la liste de toutes les cartes de la BD
    public function getAllCards()
    {
        $query = "
        SELECT
            t_card.*,
            t_collection.colName AS carCollectionName,
            t_user.useLogin AS carUserLogin,
        FROM t_card
        LEFT JOIN t_collection ON t_collection.idCollection = t_card.fkCollection
        LEFT JOIN t_user ON t_user.idUser = t_card.fkUser
    ";
        //appeler la méthode pour executer la requête
        $req = $this->querySimpleExecute($query);
        //appeler la méthode pour avoir le résultat sous forme de tableau
        $cards = $this->formatData($req);
        //retourne toutes les cartes
        return $cards;
    }

    //Recupère la liste des informations pour une carte
    public function getOneCard($id)
    {
        //avoir la requête sql pour une carte (utilisation de l'id)
        $query = "SELECT * FROM t_card, t_collection WHERE idCard = :id AND fkCollection = idCollection";
        //appeler la méthode pour executer la requête
        $bind = array('id' => $id);
        $req = $this->queryPrepareExecute($query, $bind);
        //appeler la méthode pour avoir le résultat sous forme de tableau
        $OneCard = $this->formatData($req);
        //retourne la carte
        return $OneCard[0];
    }

    public function getAllCollections()
    {
        $query = "SELECT * FROM t_collection";
        //appeler la méthode pour executer la requête
        $req = $this->querySimpleExecute($query);
        //appeler la méthode pour avoir le résultat sous forme de tableau
        $collections = $this->formatData($req);
        //retourne toutes les collections
        return $collections;
    }

    /**
     * Fonction permettant de supprimer une carte
     * @param $id int | id de la carte à supprimer
     */
    public function deleteCardById($id)
    {

        $query = "
                DELETE FROM t_card 
                WHERE idCard = :id
            ;";

        $replacements = ['id' => $id];

        $this->queryPrepareExecute($query, $replacements);
    }

    //   Fonction permettant de modifier les informations d'une carte
    //   @param $id        int | id de la carte à mettre a jour
    //   @param $card array | contient tous les attributs d'une carte à modifier
     
    public function updateCardById($id, $card)
    {
        $query = "
                UPDATE
                    t_card
                SET
                    carName = :name,
                    carDate = :date,
                    carCredits = :credits,
                    carCondition = :condition,
                    carDescription = :description,
                    carIsAvailable = :isAvailable,
                    carPhoto = :imgPath,
                    fkUser = :fkUser,
                    fkCollection = :fkCollection
                WHERE
                    idCard = :id
            ;";

        $card["id"] = $id;
        $this->queryPrepareExecute($query, $card);
    }
}
