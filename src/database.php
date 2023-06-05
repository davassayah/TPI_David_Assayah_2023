<?php

/**
 * ETML
 * Auteur: David Assayah
 * Date: 22.05.2023
 * Description: Fichier permettant de se connecter à la db et contenant toutes les fonctions utilisées
 */

class Database
{

    // Instance unique de la classe Database
    private static $instance = null;
    // Variable pour stocker l'objet PDO utilisé pour se connecter à la base de données
    private $connector;

    private function __construct()
    {  // Inclut le fichier utils.php qui contient des fonctions utilitaires
        include(__DIR__ . "/utils.php");
        // Charge les configurations de la base de données depuis le fichier config.php 
        $configs = include(__DIR__ . "/../config.php");
        try {
            $this->connector = new PDO(
                $configs['dns'],
                $configs['user'],
                // Fonction getPassword() pour obtenir le mot de passe de la base de données
                getPassword()
            );
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public static function getInstance()

    {   // Vérifie si l'instance de la classe Database n'a pas encore été créée
        if (self::$instance == null) {
            // Crée une nouvelle instance de la classe Database
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
        } 
    }

    /**
     * Fonction permettant de créer un nouvel utilisateur
     * @param $user array | contient tous les attributs d'un utilisateur à créer
     */
    public function addUser($user)
    {
        $query = "
                INSERT INTO t_user (useLogin, useEmail, useFirstName, useLastName, 
                useLocality, usePostalCode, useStreetName, useStreetNumber, usePassword) 
                VALUES (:login, :email, :firstName, :lastName, 
                :locality, :postalCode, :streetName, :streetNumber, :password);
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

    public function getUserByLogin($login)
    {
        $query = "SELECT * FROM t_user WHERE useLogin = :login";
        $bind = array('login' => $login);
        $req = $this->queryPrepareExecute($query, $bind);
        $existingUser = $this->formatData($req);

        return $existingUser[0];
    }

    public function getUserByEmail($email)
    {
        $query = "SELECT * FROM t_user WHERE useEmail = :email";
        $bind = array('email' => $email);
        $req = $this->queryPrepareExecute($query, $bind);
        $existingUser = $this->formatData($req);

        return $existingUser[0];
    }

    /**
     * Fonction permettant de créer une nouvelle carte
     * @param $card array | contient tous les attributs d'une carte à créer
     * @param $imgData array | contient tous les attributs de l'image à uploader
     */
    public function addCard($card, $imgData, $idUser)
    {
        $query = "
                INSERT INTO t_card (carName, carDate, carCredits, carCondition,
                 carDescription, carPhoto, fkUser, fkCollection) 
                VALUES (:name, :date, :credits, :condition,
                 :description, :photo, :fkUser, :fkCollection);
            ";

        $replacements = [
            'name' => $card['name'],
            'date' => intval($card['date']),
            'credits' => intval($card['credits']),
            'condition' => $card['condition'],
            'description' => $card['description'],
            'photo' => $imgData['uploadDirectoryImg'] . $imgData['fileNameImg'],
            'fkUser' => $idUser,
            'fkCollection' => $card['collection']
        ];

        $response = $this->queryPrepareExecute($query, $replacements);
    }

    public function renameFile()
    {
        $query = "
            SELECT idCard FROM t_card ORDER BY idCard desc Limit 1";
        $req = $this->querySimpleExecute($query);
        $result = $this->formatData($req);
        return  $result[0]['idCard'];
    }

    //Fonction permettant de récupérer la liste de toutes les cartes de la BD
    public function getAllCards()
    {
        $query = "
       SELECT
           t_card.*,
           t_collection.colName AS carCollectionName,
           t_user.useLogin AS carUserLogin
       FROM t_card
       LEFT JOIN t_collection ON t_collection.idCollection = t_card.fkCollection
       LEFT JOIN t_user ON t_user.idUser = t_card.fkUser
       WHERE t_card.carIsAvailable = 1
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
        $query = "
            SELECT
                t_card.*,
                t_user.useLogin AS carUserLogin
            FROM t_card
            LEFT JOIN t_collection ON t_collection.idCollection = t_card.fkCollection
            LEFT JOIN t_user ON t_user.idUser = t_card.fkUser
            WHERE t_card.idCard = :id
        ";
        //appeler la méthode pour executer la requête
        $bind = array('id' => $id);
        $req = $this->queryPrepareExecute($query, $bind);
        //appeler la méthode pour avoir le résultat sous forme de tableau
        $oneCard = $this->formatData($req);
        //retourne la carte
        return $oneCard[0];
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
                    carPhoto = :imgPath,
                    fkCollection = :collection
                WHERE
                    idCard = :id
            ;";

        $card["id"] = $id;
        $this->queryPrepareExecute($query, $card);
    }

    private function convertConditions($conditions)
    {
        $f = '';
        foreach ($conditions as $condition) {
            $f .= "'" . $condition . "', ";
        }
        return substr($f, 0, -2);
    }

    private function addWhereOrAnd($filter)
    {
        $operator = "";
        if ($filter) {
            $operator .= " AND ";
        } else {
            $operator .= " WHERE ";
        }
        return $operator;
    }

    public function sortCards($filters)
    {

        $query = "
            SELECT
                t_card.*,
                t_collection.colName AS carCollectionName,
                t_user.useLogin AS carUserLogin
            FROM t_card
            LEFT JOIN t_collection ON t_collection.idCollection = t_card.fkCollection
            LEFT JOIN t_user ON t_user.idUser = t_card.fkUser
            WHERE t_card.carIsAvailable = 1 
        ";


        $filter = false;
        $replacements = [];

        // Condition 1
        if (!empty($filters['search'])) {
            $filter = true;
            $query .= $this->addWhereOrAnd($filter);
            $query .= " t_card.carName LIKE :searchValue ";
            $replacements['searchValue'] =  '%' . $filters['search'] . '%';
        }

        // Condition 2
        if (isset($filters['conditions'])) {
            $query .= $this->addWhereOrAnd($filter);
            $query .= " t_card.carCondition IN (" . $this->convertConditions($filters['conditions'])  .  ")";
            $filter = true;
        }

        // Condition 3
        if (isset($filters['idCollection']) and $filters['idCollection'] !== '') {
            $query .= $this->addWhereOrAnd($filter);
            $query .= " t_card.fkCollection = :idCollection";
            $replacements['idCollection'] = $filters['idCollection'];
            $filter = true;
        }

        $query .= " ORDER BY t_card.carName ASC";

        $req = $this->queryPrepareExecute($query, $replacements);
        $filters = $this->formatData($req);

        return $filters;
    }

    /**
     * Crée une nouvelle transaction en insérant l'ID de l'acheteur dans la table t_order.
     *
     * @param int $idBuyer L'ID de l'acheteur
     * @return int L'ID de la dernière transaction insérée
     */
    public function createTransaction($idBuyer)
    {
        $query = "
        INSERT INTO t_order (fkUser)
        VALUES (:fkUser);
    ";

        $replacements = ['fkUser' => $idBuyer];

        // Exécute la requête préparée avec les valeurs fournies
        $this->queryPrepareExecute($query, $replacements);

        // Retourne l'ID de la dernière transaction insérée dans la base de données
        return $this->connector->lastInsertId();
    }

    /**
     * Associe une carte à une commande spécifiée en mettant à jour la colonne fkOrder de la table t_card.
     *
     * @param int $idCard L'ID de la carte
     * @param int $idOrder L'ID de la commande
     */
    public function assignCardToOrder($idCard, $idOrder)
    {
        $query = "
        UPDATE t_card
        SET fkOrder = :idOrder
        WHERE idCard = :idCard;
    ";

        $replacements = [
            'idCard' => $idCard,
            'idOrder' => $idOrder,
        ];

        // Exécute la requête préparée avec les valeurs fournies
        $this->queryPrepareExecute($query, $replacements);
    }

    /**
     * Modifie le statut de disponibilité des cartes en mettant à jour la colonne carIsAvailable de la table t_card.
     *
     * @param array $cardIds Les identifiants des cartes à indiquer comme disponible/indisponible
     * @param int $isCardAvailable La disponibilité à définir (1 pour activer, 0 pour désactiver)
     */
    public function toggleAvailabilityOfCards($cardIds, $isCardAvailable = 1)
    {
        // Convertit le tableau d'identifiants de cartes en une chaîne séparée par des virgules
        $cardIds = implode(',', $cardIds);

        $query = "
        UPDATE t_card
        SET carIsAvailable = :isCardAvailable
        WHERE idCard IN ($cardIds);
    ";

        $replacements = ['isCardAvailable' => $isCardAvailable];

        $this->queryPrepareExecute($query, $replacements);
    }

    /**
     * Récupère et classe les cartes selon le propriétaire à partir des identifiants des cartes présentes dans le panier.
     *
     * @param array $cardIds Les identifiants des cartes
     * @return array Les cartes classées par propriétaire
     */
    public function getAndOrderCardsByOwner($cardIds)
    {
        $userCards = []; // Tableau vide pour stocker les cartes classées par propriétaire

        foreach ($cardIds as $idCard) {
            $query = "SELECT * FROM t_card WHERE idCard = :id";
            $bind = array('id' => $idCard);


            $req = $this->queryPrepareExecute($query, $bind);

            $card = $this->formatData($req);

            // Vérifie si le propriétaire de la carte existe déjà dans le tableau $userCards
            if (!isset($userCards[$card[0]['fkUser']])) {
                // Si le propriétaire n'existe pas, initialise un tableau vide pour stocker ses cartes
                $userCards[$card[0]['fkUser']] = [];
            }

            // Ajoute la carte à la liste des cartes du propriétaire correspondant
            array_push($userCards[$card[0]['fkUser']], $card[0]);
        }

        return $userCards;
    }

    /**
     * Vérifie si l'utilisateur dispose d'assez de crédits pour les cartes présentes dans sa commande.
     *
     * @param array $userCards Cartes de l'utilisateur classées par propriétaire
     * @return bool Indique si l'utilisateur a suffisamment de crédits (true) ou non (false)
     */
    public function hasUserEnoughCredits($userCards)
    {
        $totalCardsCredits = 0; // Variable pour stocker le total des crédits des cartes

        foreach ($userCards as $idCardOwner => $cards) {
            foreach ($cards as $card) {
                // Ajoute les crédits de chaque carte au total des crédits des cartes
                $totalCardsCredits = $totalCardsCredits + $card['carCredits'];
            }
        }

        // Vérifie si le nombre de crédits de l'utilisateur moins le total des crédits des cartes est inférieur à 0
        if (($_SESSION['useCredits'] - $totalCardsCredits) < 0) {
            return false;
        }

        return true;
    }

    /**
     * Met à jour le nombre de crédits dont dispose l'acheteur en soustrayant les crédits de la carte dans son panier de son total de crédits personnel.
     *
     * @param int $idCard L'ID de la carte
     * @param int $idBuyer L'ID de l'acheteur
     */
    public function substrCreditsOfBuyer($idCard, $idBuyer)
    {
        $query = "
        UPDATE t_user
        SET useCredits = (SELECT SUM(t_user.useCredits - 
        (SELECT t_card.carCredits FROM t_card WHERE t_card.idCard = :idCard)))
        WHERE t_user.idUser = :idUser;
    ";

        $replacements = [
            'idCard' => $idCard,
            'idUser' => $idBuyer,
        ];

        $this->queryPrepareExecute($query, $replacements);
    }

    /**
     * Fonction permettant de créer une nouvelle commande
     * @param int   $idBuyer | ID de l'utilisateur qui passe la commande
     * @param array $cardIds | Tableau contenant les IDs des cartes qui vont être achetées
     * 
     * @return boolean Retourne true si la commande est valide ou false si l'utilisateur n'a pas suffisamment de crédits.
     */
    public function createOrder($idBuyer, $cardIds)
    {
        // Récupère les cartes et les organise par propriétaire
        $userCards = $this->getAndOrderCardsByOwner($cardIds);

        // Vérifie si l'utilisateur a suffisamment de crédits pour passer la commande
        if ($this->hasUserEnoughCredits($userCards) == false) {
            return false;
        }

        // Modifie la disponibilité des cartes
        $this->toggleAvailabilityOfCards($cardIds, 0);

        foreach ($userCards as $idCardOwner => $cards) {
            // Crée une transaction pour l'acheteur
            $transactionId = $this->createTransaction($idBuyer);

            // Pour chaque carte, assigne la carte à la commande,
            // met à jour les crédits de l'acheteur, 
            //se met à jour les crédits de session et modifie la nouvelle valeur en DB
            foreach ($cards as $card) {
                $this->assignCardToOrder($card['idCard'], $transactionId);
                $this->substrCreditsOfBuyer($card['idCard'], $idBuyer);
                $_SESSION['useCredits'] = $this->getOneUser($idBuyer)['useCredits'];
            }
        }

        return true;
    }

    /**
     * Fonction permettant de récupérer toutes les commandes de l'utilisateur
     * @param int $idUser | ID de l'utilisateur
     * 
     * @return array $orders | Liste des commandes de l'utilisateur
     */
    public function getAllOrdersByUserId($idUser)
    {
        $query = "
        SELECT
            t_order.*,
            (SELECT SUM(t_card.carCredits) FROM t_card WHERE t_card.fkOrder = t_order.idOrder) AS ordCredits,
            (
                SELECT 
                    t_user.useLogin
                FROM t_card
                LEFT JOIN t_user ON t_user.idUser = t_card.fkUser
                WHERE t_card.fkOrder = t_order.idOrder
                LIMIT 1
            ) AS ordCardsOwner
        FROM t_order
        WHERE t_order.fkUser = :idUser
    ";

        $replacements = ['idUser' => $idUser];

        $req = $this->queryPrepareExecute($query, $replacements);

        $orders = $this->formatData($req);

        // Pour chaque commande, récupère les cartes associées
        foreach ($orders as $index => $order) {
            // Requête pour récupérer les cartes associées à la commande
            $query = "
            SELECT
                *
            FROM t_card
            WHERE t_card.fkOrder = :idOrder
        ";

            $replacements = ['idOrder' => $order['idOrder']];

            $req = $this->queryPrepareExecute($query, $replacements);

            // Associe les cartes récupérées à la commande
            $orders[$index]['ordCards'] = $this->formatData($req);
        }

        return $orders;
    }

    /**
     * Fonction permettant de confirmer la réception de la commande
     * @param int $idOrder | ID de la commande à confirmer
     */
    public function confirmOrderReception($idOrder)
    {
        // Requête pour calculer le total des crédits des cartes associées à la commande
        $query = "
        SELECT
            SUM(carCredits) as total
        FROM t_card
        WHERE t_card.fkOrder = :idOrder
    ";

        $replacements = ['idOrder' => $idOrder];

        // Exécute la requête préparée avec les valeurs fournies
        $req = $this->queryPrepareExecute($query, $replacements);

        // Récupère le total des crédits sous forme de tableau
        $totalOrderCredits = $this->formatData($req);

        // Requête pour mettre à jour le statut de la commande en tant que "processed"
        $query = "
        UPDATE t_order
        SET ordStatus = 'processed'
        WHERE idOrder = :idOrder;
    ";

        // Exécute la requête préparée avec les valeurs fournies
        $this->queryPrepareExecute($query, $replacements);

        // Requête pour mettre à jour les crédits de l'utilisateur en ajoutant les crédits de la commande
        $query = "
        UPDATE t_user
        SET useCredits = (SELECT SUM(t_user.useCredits + :creditsToAdd))
        WHERE idUser = (
            SELECT
                t_card.fkUser
            FROM t_card
            WHERE t_card.fkOrder = :idOrder
            LIMIT 1
        );
    ";

        $replacements = [
            'idOrder' => $idOrder,
            'creditsToAdd' => $totalOrderCredits[0]['total'],
        ];

        // Exécute la requête préparée avec les nouvelles valeurs de remplacements
        $this->queryPrepareExecute($query, $replacements);
    }
}
