<?php
/**
 * Fonction permettant de créer les donnees necessaires a la validation de la photo a telecharger
 * @param array $dataFiles | Contient les donnees de l'image a telecharger
 * @param array $card      | Contient les donnees de la carte a mettre a jour
 * 
 * @return array $imageData | Contient les donnees necessaires a la validation de la photo a telecharger
 */
function normalizeImgData($dataFiles, $card) {
    $imageData = [];
    //Gestion du transfert de l'image
    //prends le dossier actuel
    $imageData["currentDirectory"] = getcwd();
    //dossier vers lequel le fichier va être transféré
    $imageData["uploadDirectoryImg"] = "\img\photos";
    //Récupère le fichier
    $imageData["downloadImg"] = $dataFiles["downloadImg"];
    //Récupère le nom du fichier
    $imageData["fileNameImg"] = $dataFiles['downloadImg']['name'];
    //Récupère le nom temporaire du fichier
    $imageData["fileTmpNameImg"] = $dataFiles['downloadImg']['tmp_name'];
    //Reprends l'extension du fichier transféré
    $tmp = explode('.', $imageData["fileNameImg"]);
    $imageData["fileExtensionImg"] = strtolower(end($tmp));
    //Definis l'extension du fichier apres l'avoir recuperee
    $imageData["extensionImg"] = pathinfo($imageData["fileNameImg"], PATHINFO_EXTENSION);
    //permet de donner un nom final au fichier
    $imageData["imgPath"] = $card['carPhoto'];

    $imageData["filePath"] = "." . $imageData["imgPath"];
    // Définis le chemin final avec le nom du fichier où va être transférer le fichier en lui donnant un nom unique
    $imageData["uploadPathImg"] = $imageData["uploadDirectoryImg"] . $imageData["fileNameImg"];

    return $imageData;
}

/**
 * Fonction permettant de supprimer l'ancienne image sur le serveur
 * @param array $imageData | Contient les donnees de l'image a telecharger deja normalisees
 */
function deletePreviousImg($imageData) {
    if (file_exists($imageData["filePath"]) and ($imageData["extensionImg"] == "jpg")) {
        unlink($imageData["filePath"]);
    }
}


