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
     * Fonction permettant d'authentifier l'utilisateur
     * @param $user string | login de l'utilisateur
     * @param $password string | mot de passe de l'utilisateur
     */
    public function CheckAuth($useLogin, $password)
    {
        $query = "
                SELECT * 
                FROM t_user 
                WHERE useLogin = :useLogin
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

    //Fonction permettant de récupérer la liste de toutes les cartes de la BD
    public function getAllCards()
    {
        $query = "
                   SELECT
                       tc.*,
                       tc.colName AS carCollectionName
                   FROM t_card tt
                   LEFT JOIN t_collection tc ON tc.idCollection = tc.fkCollection
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
        //retourne l'enseignant
        return $OneCard[0];
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

    /**
     * Fonction permettant de créer une nouvelle carte
     * @param $card array | contient tous les attributs d'une carte à créer
     * @param $imgData array | contient tous les attributs de l'image à uploader
     */
    public function addCard($card, $imgData)
    {
        $query = "
                INSERT INTO t_card (carName, carDate, carCredits, carCondition, carDescription, carStatus, carPhoto, fkCollection) 
                VALUES (:name, :date, :credits, :condition, :description, :status, :photo, :fk_collection);
            ";

        $replacements = [
            'name' => $card['name'],
            'date' => $card['date'],
            'credits' => $card['credits'],
            'condition' => $card['condition'],
            'description' => $card['description'],
            'status' => $card['status'],
            'photo' => $card['uploadDirectoryImg'] . $imgData['fileNameImg'],
            'fk_collection' => $card['collection'],
        ];

        $response = $this->queryPrepareExecute($query, $replacements);
    }

    /**
     * Fonction permettant de modifier les informations d'une carte
     * @param $id        int | id de la carte à mettre à jour
     * @param $card array | contient tous les attributs d'une carte à modifier
     */
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
                    carStatus = :status,
                    carPhoto = imgPath,
                    fkCollection = :collection
                WHERE
                    idCard = :id
            ;";

        $card["id"] = $id;
        $this->queryPrepareExecute($query, $card);
    }
}