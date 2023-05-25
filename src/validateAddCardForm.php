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

const REGEX_STRING = '/^[a-zàâçéèêîïôûù -]{2,30}$/mi';

function validationAddCardForm($db)
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
            'condition' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'description'   => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'collection' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            // on ne filtre pas les 3 champs car on veut effectuer une validation par REGEX
            // tout en affichant une erreur précise à l'utilisateur
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
    }

    // le champ date :
    // - est obligatoire
    // - doit être une string entre 2 et 30 caractères
    // - répondant à la REGEX 'REGEX_STRING'

    if (!$date) {
        $errors['date'] = ERROR_DATE_REQUIRED;
    // } elseif (!filter_var(
    //     $name,
    //     FILTER_VALIDATE_REGEXP,
    //     array(
    //         "options" => array("regexp" => REGEX_STRING)
    //     )
    // )) {
    //     $errors["firstName"] = ERROR_STRING;

    }

    // le champ crédits
    // - est obligatoire
    // - doit être une string entre 2 et 30 caractères
    // - répondant à la REGEX 'REGEX_STRING'
    if (!$credits) {
        $errors['credits'] = ERROR_CREDITS_REQUIRED;
    // } elseif (!filter_var(
    //     $name,
    //     FILTER_VALIDATE_REGEXP,
    //     array(
    //         "options" => array("regexp" => REGEX_STRING)
    //     )
    // )) {
    //     $errors["name"] = ERROR_STRING;
    }

    // le champ état
    // - est obligatoire
    // - doit être une string entre 2 et 30 caractères
    // - répondant à la REGEX 'REGEX_STRING'
    if (!$condition) {
        $errors['condition'] = ERROR_CONDITION_REQUIRED;
    // } elseif (!filter_var(
    //     $nickName,
    //     FILTER_VALIDATE_REGEXP,
    //     array(
    //         "options" => array("regexp" => REGEX_STRING)
    //     )
    // )) {
    //     $errors["nickName"] = ERROR_STRING;
    }

    // le champ description est obligatoire
    if (!$description) {
        $errors['origin'] = ERROR_DESCRIPTION_REQUIRED;
    }

    // le champ collection est obligatoire et ne peut donc pas avoir
    // la valeur "Section"
    if (!$collection || $collection === "Collection") {
        $errors['section'] = ERROR_COLLECTION_REQUIRED;
    }

    if (!$downloadImg) {
        $errors['downloadImg'] = ERROR_IMAGE_REQUIRED;
    } elseif (!in_array($imageData['extensionImg'], ['jpg'])) {
        $errors['downloadImg'] = ERROR_IMAGE_EXTENSION;
    }
    return ["cardData" => $cardData, "errors" => $errors];
}


