<?php

/**
 * ETML
 * Auteur: David Assayah
 * Date: 25.05.2023
 * Description: Fichier permettant la validation des données saisies par l'utilisateur lors de la création du compte
 */
// Erreurs si le champ est obligatoire

const ERROR_LOGIN_REQUIRED = "Veuillez renseigner le champ login";
const ERROR_EMAIL_REQUIRED = "Veuillez renseigner le champ email";
const ERROR_FIRSTNAME_REQUIRED = "Veuillez renseigner le champ prénom";
const ERROR_LASTNAME_REQUIRED = "Veuillez renseigner le champ nom de famille";
const ERROR_LOCALITY_REQUIRED = "Veuillez renseigner le champ localité";
const ERROR_POSTALCODE_REQUIRED = "Veuillez renseigner le champ code postal";
const ERROR_STREETNAME_REQUIRED = "Veuillez renseigner le champ nom de la rue";
const ERROR_STREETNUMBER_REQUIRED = "Veuillez renseigner le champ numéro de la rue";

// Erreurs spécifiques
const ERROR_LOGIN = "Le champ doit avoir un nombre de caractères entre 1 et 120 tous caractères compris";
const ERROR_STRING = "Pour ce champ, vous devez saisir une chaîne entre 2 et 30 caractères mais seuls " .
    "les caractères suivants sont autorisés : les lettres de a à z (minuscules ou majuscules), les accents, " .
    "l'espace, le tiret et l'apostrophe";
const ERROR_EMAIL_FORMAT = "Merci de renseigner une adresse email valide";
const ERROR_VARCHAR120_WITHOUT_NUMBERS = "Merci de saisir une châine de caractères entre 1 et 120 caractères ne contenant pas de chiffres.";
const ERROR_VARCHAR15 = "Merci de saisir une chaîne de caractères de 1 à 15 caractères maximum";
const ERROR_VARCHAR15_WITHOUT_SPECIAL_CHARS = "Merci de saisir une chaîne de caractères de 1 à 15 caractères maximum - chiffres et lettres compris - sans caractères spéciaux";
const ERROR_LOGIN_EXISTS = "Ce login est déjà utilisé";
const ERROR_EMAIL_EXISTS = "Cette adresse email est déjà utilisée";

//REGEX
const REGEX_VARCHAR120_WITHOUT_NUMBERS = '/^(?!.*\n.*$)(?!\n)(?!.{121})(?:[A-Z][a-z]{0,119}|[a-z]{1,120})$/u';
const REGEX_VARCHAR120 = '/^(?!.*\n.*$)(?!\n)(?!.{121}).{1,120}$/us';
const REGEX_VARCHAR15 = '/^(?!.*\n.*$)(?!\n)(?!.{16}).{1,15}$/us';
const REGEX_VARCHAR15_WITHOUT_SPECIAL_CHARS = '/^(?![0-9]{16})[a-zA-Z0-9]{1,15}$/';

function validateAddUserForm($db)
{
    // On commence par désinfecter les données saisies par l'utilisateur
    // ainsi on se protège contre les attaques de types XSS

    $userData = filter_input_array(
        INPUT_POST,
        [
            'login' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'email' => FILTER_SANITIZE_EMAIL,
            'firstName' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'lastName' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'locality' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'postalCode' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'streetName' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'streetNumber' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        ]
    );

    // Si certains champs n'ont pas été saisis alors on donne la valeur ''
    $login = $userData['login'] ?? '';
    $email = $userData['email'] ?? '';
    $firstName = $userData['firstName'] ?? '';
    $lastName = $userData['lastName'] ?? '';
    $locality = $userData['locality'] ?? '';
    $postalCode = $userData['postalCode'] ?? '';
    $streetName = $userData['streetName'] ?? '';
    $streetNumber = $userData['streetNumber'] ?? '';

    $errors = [];

    //
    // Validation des données
    //

    // le champ login est obligatoire
    if (!$login) {
        $errors['login'] = ERROR_LOGIN_REQUIRED;
    } elseif (!preg_match(REGEX_VARCHAR120, $login)) {
        $errors["login"] = ERROR_LOGIN;
    } elseif ($existingUser = $db->getUserByLogin($login)) {
        $errors['login'] = ERROR_LOGIN_EXISTS;
    }

    // le champ email :
    // - est obligatoire
    // - doit être une adresse email valide
    if (!$email) {
        $errors['email'] = ERROR_EMAIL_REQUIRED;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = ERROR_EMAIL_FORMAT;
    } elseif ($existingUser = $db->getUserByEmail($email)) {
        $errors['email'] = ERROR_EMAIL_EXISTS;
    }

    // le champ prénom :
    // - est obligatoire
    // - doit être une chaîne entre 2 et 30 caractères
    // - répondant à la REGEX 'REGEX_STRING'
    if (!$firstName) {
        $errors['firstName'] = ERROR_FIRSTNAME_REQUIRED;
    } elseif (!preg_match(REGEX_VARCHAR120_WITHOUT_NUMBERS, $firstName)) {
        $errors["firstName"] = ERROR_VARCHAR120_WITHOUT_NUMBERS;
    }

    // le champ nom de famille
    // - est obligatoire
    // - doit être une chaîne entre 2 et 30 caractères
    // - répondant à la REGEX 'REGEX_STRING'
    if (!$lastName) {
        $errors['lastName'] = ERROR_LASTNAME_REQUIRED;
    } elseif (!preg_match(REGEX_VARCHAR120_WITHOUT_NUMBERS, $lastName)) {
        $errors["lastName"] = ERROR_VARCHAR120_WITHOUT_NUMBERS;
    }

    // le champ localité est obligatoire
    if (!$locality) {
        $errors['locality'] = ERROR_LOCALITY_REQUIRED;
    } elseif (!preg_match(REGEX_VARCHAR120_WITHOUT_NUMBERS, $locality)) {
        $errors["locality"] = ERROR_VARCHAR120_WITHOUT_NUMBERS;
    }

    // le champ code postal est obligatoire
    if (!$postalCode) {
        $errors['postalCode'] = ERROR_POSTALCODE_REQUIRED;
    } elseif (!preg_match(REGEX_VARCHAR120_WITHOUT_NUMBERS, $postalCode)) {
        $errors["postalCode"] = ERROR_VARCHAR120_WITHOUT_NUMBERS;
    }

    // le champ nom de la rue est obligatoire
    if (!$streetName) {
        $errors['streetName'] = ERROR_STREETNAME_REQUIRED;
    } elseif (!preg_match(REGEX_VARCHAR120_WITHOUT_NUMBERS, $streetName)) {
        $errors["streetName"] = ERROR_VARCHAR120_WITHOUT_NUMBERS;
    }

    // le champ numéro de la rue est obligatoire
    if (!$streetNumber) {
        $errors['streetNumber'] = ERROR_STREETNUMBER_REQUIRED;
    } elseif (!preg_match(REGEX_VARCHAR15_WITHOUT_SPECIAL_CHARS, $streetNumber)) {
        $errors["streetNumber"] = ERROR_VARCHAR15_WITHOUT_SPECIAL_CHARS;
    }

    return ["userData" => $userData, "errors" => $errors];
}
