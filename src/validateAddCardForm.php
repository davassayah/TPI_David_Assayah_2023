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
const ERROR_LENGTH             = "Le champ doit avoir un nombre de caractères entre 2 et 30";
const ERROR_STRING             = "Pour ce champ, vous devez saisir une chaine entre 2 et 30 caractères mais seuls " .
    " les caractères suivant sont autorisés : les lettres de a à z (minuscules ou majuscules), les accents, " .
    "l'espace, le - et le '";
const ERROR_REGEX_VARCHAR45_WITH_SPECIAL_CHARS = "Merci de renseigner une chaîne de caractères valide, de 1 à 45 caractères pouvant contenir des tirets, des espaces et des apostrophes, ainsi que des lettres accentuées.";
const ERROR_DATE = "Merci de renseigner une chaîne de caractères qui sont uniquement des chiffres et uniquement 4 caractères.";
const ERROR_CREDITS = "Merci de renseigner une chaîne de caractères qui composée de 1 à 3 chiffres et dont le premier caractère n'est pas 0";
const ERROR_DESCRIPTION = "Merci de renseigner une chaîne de caractères qui n'excède pas 255 caractères";
//REGEX
const REGEX_VARCHAR45_WITH_SPECIAL_CHARS = '/^(?!.*[^a-zA-Z0-9àáâäçèéêëìíîïñòóôöùúûüÿ\s\'-]{16})[a-zA-Z0-9àáâäçèéêëìíîïñòóôöùúûüÿ\s\'-]{1,15}$/u';
const REGEX_DATE = '/^\d{4}$/';
const REGEX_CREDITS = '/^(?!0$)(?!.*\d{4})(?!0\d)\d{1,3}$/';
const REGEX_VARCHAR255 = '/^(?!.*\n.*$)(?!\n)(?!.{256}).{1,255}$/us';



const REGEX_STRING = '/^[a-zàâçéèêîïôûù -]{2,30}$/mi';

function validateAddCardForm($db)
{

    // ATTENTION
    // Si on désinfecte les data avec FILTER_SANITIZE_FULL_SPECIAL_CHARS
    // on obtient des strings qui ont été modifiées avec des < ou > ou & etc
    // donc après on ne peut plus faire de validation avec des REGEX précise ...
    // de plus lorsque l'on affiche une variable après avoir été désinfectée, on ne voit pas, dans le navigateur,
    // les caractères < ou > ou & etc car le navigateur les re-transforme
    // Donc on ne sanitize pas ci-dessous certains champs car on veut leur appliiquer une REGEX particulière

    // On commence par désinfecter les données saisies par l'utilisateur
    // ainsi on se protège contre les attaques de types XSS

    $cardData = filter_input_array(
        INPUT_POST,
        [
            'name' => $_POST['name'],
            'date'  => $_POST['date'],
            'credits' => $_POST['credits'],
            'condition' => $_POST['condition'],
            'description'   => $_POST['description'],
            'collection' => $_POST['collection'],
        ]
    );

    $imageData = addImages($_FILES, $db);

    $cardData['imageData'] = $imageData;

    // Si certains champs n'ont pas été saisies alors on donne la valeur ''
    $name = $cardData['name'] ?? '';
    $date = $cardData['date']  ?? '';
    $credits  = $cardData['credits']  ?? '';
    $condition    = $cardData['condition']    ?? '';
    $description   = $cardData['description']   ?? '';
    $downloadImg = $cardData['imageData']['downloadImg']['size'] ?? '';
    $collection   = $cardData['collection']   ?? '';

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
    // - doit être une string entre 2 et 30 caractères
    // - répondant à la REGEX 'REGEX_STRING'
    if (!$date) {
        $errors['date'] = ERROR_DATE_REQUIRED;
    } elseif (!preg_match(REGEX_DATE, $date)) {
        $errors["date"] = ERROR_DATE;
    }

    // le champ crédits
    // - est obligatoire
    // - doit être une string entre 2 et 30 caractères
    // - répondant à la REGEX 'REGEX_STRING'
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
    // la valeur "Section"
    if (!$collection || $collection === "Collection") {
        $errors['collection'] = ERROR_COLLECTION_REQUIRED;
    }

    if (!$downloadImg) {
        $errors['downloadImg'] = ERROR_IMAGE_REQUIRED;
    } elseif (!in_array($imageData['extensionImg'], ['jpg','JPG'])) {
        $errors['downloadImg'] = ERROR_IMAGE_EXTENSION;
    }

    return ["cardData" => $cardData, "errors" => $errors];
}
