# Echange de cartes à collectionner

## Prérequis
- Windows 10 au minimum
- PHP v8.2.1
- Docker v20.10.17, build 100c701
- Visual Studio Code v1.77.3
- Git v2.37.0.windows.1

## Telechargement et lancement du projet

```bash
# Clone le project
$ git clone https://github.com/davassayah/TPI_David_Assayah_2023.git

# Entre dans le dossier source du projet
$ cd src

# Lance un serveur PHP sur le port 3000
$ php -S localhost:3000
```

## Configurations

Afin de pouvoir lancer le serveur correctement et de pouvoir se connecter a la bonne base de donnees, il vous faudra:

- Executer le fichier de migration MySQL dans phpMyAdmin
- Creer un fichier `secrets.json` en prenant pour exemple le fichier `secrets_example.json`
- Verifier que vous avez correctement mis a jour la configuration mysql dans le fichier `config.php`

### Migration initiale

Pour que le site puisse se connecter a la base de donnees vous devez lancer le script de migration depuis phpMyAdmin

1. Ouvrez Docker et cliquer sur le lien d'ouverture (surligner en jaune)

![1](/doc/images/1.jpg)

2. Connectez-vous avec votre utilisateur

![2](/doc/images/2.jpg)

3. Cliquez sur `Importer`

![3](/doc/images/3.jpg)

4. Importer le script de migration et cliquez sur le bouton en bas de page `Importer`

![4](/doc/images/4.jpg)

5. Vous devriez voir apparaitre la nouvelle base de donnees avec toutes les tables.

### Creation du secrets.json

DB_PASSWORD corresspond au mot de passe de l'utilisateur mysql se connectant a la base de donnees

```json
// secrets.json

{
    "password": "DB_PASSWORD"
}
```

### Configuration MySQL

Dans le fichier `config.php` mettez a jour si necessaire les informations.

```php
// config.php

$configs = array(
    'db'      => "mysql",
    'host'    => "localhost",
    'port'    => "6033",
    'dbname'  => "echangeDeCartesACollectionner",
    'charset' => "utf8",
    'user'    => 'root',
);
```