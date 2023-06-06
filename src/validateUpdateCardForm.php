<?php

/**
 * ETML
 * Auteur: David Assayah
 * Date: 25.05.2023
 * Description: Fichier permettant la validation des données saisies par l'utilisateur lors de l'ajout d'une carte
 */

//Erreurs si le champ est obligatoire
const ERROR_NAME_REQUIRED = "Veuillez renseigner le champ nom de la carte";
const ERROR_DATE_REQUIRED  = "Veuillez renseigner le champ date de la carte";
const ERROR_CREDITS_REQUIRED  = "Veuillez renseigner le champ credits de la carte";
const ERROR_CONDITION_REQUIRED    = "Veuillez renseigner le champ état de la carte";
const ERROR_DESCRIPTION_REQUIRED    = "Veuillez renseigner le champ description de la carte";
const ERROR_IMAGE_REQUIRED    = "Veuillez ajouter l'image de la carte";
const ERROR_IMAGE_EXTENSION    = "Seul le format jpg est accepté";
const ERROR_COLLECTION_REQUIRED   = "Veuillez renseigner le champ collection de la carte";

//Erreurs spécifiques à certains champs
const ERROR_REGEX_VARCHAR45_WITH_SPECIAL_CHARS = "Merci de renseigner une chaîne de caractères valide, de 1 à 45 caractères pouvant contenir des tirets, des espaces et des apostrophes, ainsi que des lettres accentuées.";
const ERROR_DATE = "Merci de renseigner une chaîne de caractères qui sont uniquement des chiffres et uniquement 4 caractères.";
const ERROR_CREDITS = "Merci de renseigner une chaîne de caractères qui composée de 1 à 3 chiffres et dont le premier caractère n'est pas 0";
const ERROR_DESCRIPTION = "Merci de renseigner une chaîne de caractères qui n'excède pas 255 caractères";

//REGEX
const REGEX_VARCHAR45_WITH_SPECIAL_CHARS = "/^[\p{L}0-9\s'-]{1,45}$/u";
const REGEX_DATE = '/^\d{4}$/';
const REGEX_CREDITS = '/^(?!0$)(?!.*\d{4})(?!0\d)\d{1,3}$/';
const REGEX_VARCHAR255 = '/^(?!.*\n.*$)(?!\n)(?!.{256}).{1,255}$/us';

function validateUpdateCardForm($imageData)
{
    // Si certains champs n'ont pas été saisies alors on donne la valeur ''
    $name = $_POST['name'] ?? '';
    $date = $_POST['date']  ?? '';
    $credits  = $_POST['credits']  ?? '';
    $condition    = $_POST['condition']    ?? '';
    $description   = $_POST['description']   ?? '';
    $downloadImg = $imageData['downloadImg']['size'] ?? '';
    $collection   = $_POST['collection']   ?? '';

    $errors = [];

    //
    // Validation des données
    //

    // le champ nom est obligatoire
    if (!$name) {
        $errors['name'] = ERROR_NAME_REQUIRED;
    } elseif (!preg_match(REGEX_VARCHAR45_WITH_SPECIAL_CHARS, $name)) {
        $errors["name"] = ERROR_REGEX_VARCHAR45_WITH_SPECIAL_CHARS;
    }

    // le champ date :
    // - est obligatoire
    // -
    if (!$date) {
        $errors['date'] = ERROR_DATE_REQUIRED;
    } elseif (!preg_match(REGEX_DATE, $date)) {
        $errors["date"] = ERROR_DATE;
    }

    // le champ crédits
    // - est obligatoire
    // - 
    if (!$credits) {
        $errors['credits'] = ERROR_CREDITS_REQUIRED;
    } elseif (!preg_match(REGEX_CREDITS, $credits)) {
        $errors["credits"] = ERROR_CREDITS;
    }

    // le champ état
    // - est obligatoire
    if (!$condition) {
        $errors['condition'] = ERROR_CONDITION_REQUIRED;
    }

    // le champ description est obligatoire
    if (!$description) {
        $errors['description'] = ERROR_DESCRIPTION_REQUIRED;
    } elseif (!preg_match(REGEX_VARCHAR255, $description)) {
        $errors["description"] = ERROR_DESCRIPTION;
    }

    // le champ collection est obligatoire et ne peut donc pas avoir
    // la valeur "Collection"
    if (!$collection || $collection === "Collection") {
        $errors['collection'] = ERROR_COLLECTION_REQUIRED;
    }

    // le champ downloadImg 
    // - est obligatoire
    // - son extension doit être jpg ou JPG
    if (!$downloadImg) {
        $errors['downloadImg'] = ERROR_IMAGE_REQUIRED;
    } elseif (!in_array($imageData['extensionImg'], ['jpg','JPG'])) {
        $errors['downloadImg'] = ERROR_IMAGE_EXTENSION;
    }

    // Vérifie s'il n'y a que "downloadImg" comme erreur dans le tableau
    if (count($errors) === 1 && isset($errors['downloadImg'])) {
        // Si c'est le cas, supprime l'erreur "downloadImg"
        $errors = [];
    }

    // On désinfecte les données saisies par l'utilisateur
    // pour se protéger contre les attaques de types XSS
    $cardData = filter_input_array(
        INPUT_POST,
        [
            'name' => $_POST['name'],
            'date'  => $_POST['date'],
            'credits' => $_POST['credits'],
            'condition' => $_POST['condition'],
            'description'   => $_POST['description'],
            'collection' => $_POST['collection']
        ]
    );

    // On reference le chemin de l'image
    $cardData["imgPath"] = $imageData["imgPath"];

    return ["cardData" => $cardData, "errors" => $errors];
}
