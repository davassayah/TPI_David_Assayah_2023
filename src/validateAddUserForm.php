<?php

/**
 * ETML
 * Auteur: David Assayah
 * Date: 25.05.2023
 * Description: Fichier permettant la validation des données saisies par l'utilisateur lors de la création du compte
 */

//Erreurs si le champ est obligatoire
const ERROR_LOGIN_REQUIRED = "Veuillez renseigner le champ login";
const ERROR_EMAIL_REQUIRED  = "Veuillez renseigner le champ email";
const ERROR_FIRSTNAME_REQUIRED  = "Veuillez renseigner le champ prénom";
const ERROR_LASTNAME_REQUIRED    = "Veuillez renseigner le champ nom de famille";
const ERROR_LOCALITY_REQUIRED    = "Veuillez renseigner le champ localité";
const ERROR_POSTALCODE_REQUIRED    = "Veuillez renseigner le champ code postal";
const ERROR_STREETNAME_REQUIRED    = "Veuillez renseigner le champ nom de la rue";
const ERROR_STREETNUMBER_REQUIRED   = "Veuillez renseigner le champ numéro de la rue";
//Erreurs spécifiques
const ERROR_LENGTH             = "Le champ doit avoir un nombre de caractères entre 2 et 30";
const ERROR_STRING             = "Pour ce champ, vous devez saisir une chaine entre 2 et 30 caractères mais seuls " .
    " les caractères suivant sont autorisés : les lettres de a à z (minuscules ou majuscules), les accents, " .
    "l'espace, le - et le '";

const REGEX_STRING = '/^[a-zàâçéèêîïôûù -]{2,30}$/mi';

function validationAddUserForm($db)
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

    $userData = filter_input_array(
        INPUT_POST,
        [
            'login' => $_POST['login'],
            'email'  => $_POST['email'],
            'firstName' => $_POST['firstName'],
            'lastName' => $_POST['lastName'],
            'locality' => $_POST['locality'],
            'postalCode' => $_POST['postalCode'],
            'streetName' => $_POST['streetName'],
            'streetNumber' => $_POST['streetNumber'],
        ]
    );

    // Si certains champs n'ont pas été saisi alors on donne la valeur ''
    $login = $userData['login'] ?? '';
    $email = $userData['email']  ?? '';
    $firstName  = $userData['firstName']  ?? '';
    $lastName    = $userData['lastName']    ?? '';
    $locality   = $userData['locality']   ?? '';
    $postalCode   = $userData['postalCode']   ?? '';
    $streetName   = $userData['streetName']   ?? '';
    $streetNumber   = $userData['streetNumber']   ?? '';

    $errors = [];

    //
    // Validation des données
    //

    // le champ login est obligatoire
    if (!$login) {
        $errors['login'] = ERROR_LOGIN_REQUIRED;
    }

    // le champ email :
    // - est obligatoire
    // - doit être une string entre 2 et 30 caractères
    // - répondant à la REGEX 'REGEX_STRING'

    if (!$email) {
        $errors['email'] = ERROR_EMAIL_REQUIRED;
        // } elseif (!filter_var(
        //     $name,
        //     FILTER_VALIDATE_REGEXP,
        //     array(
        //         "options" => array("regexp" => REGEX_STRING)
        //     )
        // )) {
        //     $errors["firstName"] = ERROR_STRING;

    }

    // le champ prénom :
    // - est obligatoire
    // - doit être une string entre 2 et 30 caractères
    // - répondant à la REGEX 'REGEX_STRING'
    if (!$firstName) {
        $errors['firstName'] = ERROR_FIRSTNAME_REQUIRED;
        // } elseif (!filter_var(
        //     $name,
        //     FILTER_VALIDATE_REGEXP,
        //     array(
        //         "options" => array("regexp" => REGEX_STRING)
        //     )
        // )) {
        //     $errors["name"] = ERROR_STRING;
    }

    // le champ nom de famille
    // - est obligatoire
    // - doit être une string entre 2 et 30 caractères
    // - répondant à la REGEX 'REGEX_STRING'
    if (!$lastName) {
        $errors['lastName'] = ERROR_LASTNAME_REQUIRED;
        // } elseif (!filter_var(
        //     $nickName,
        //     FILTER_VALIDATE_REGEXP,
        //     array(
        //         "options" => array("regexp" => REGEX_STRING)
        //     )
        // )) {
        //     $errors["nickName"] = ERROR_STRING;
    }

    // le champ localité est obligatoire
    if (!$locality) {
        $errors['locality'] = ERROR_LOCALITY_REQUIRED;
    }

    // le champ code postal est obligatoire
    if (!$postalCode) {
        $errors['postalCode'] = ERROR_POSTALCODE_REQUIRED;
    }

    // le champ nom de la rue est obligatoire
    if (!$streetName) {
        $errors['streetName'] = ERROR_STREETNAME_REQUIRED;
    }

    // le champ numéro de la rue est obligatoire
    if (!$streetNumber) {
        $errors['streetNumber'] = ERROR_STREETNUMBER_REQUIRED;
    }

    return ["userData" => $userData, "errors" => $errors];
}
