<?php
function readMedecinID($id, $linkpdo)
{
    $sql = "SELECT * FROM medecin WHERE id_medecin = :id";
    if ($sql == false) {
        $reponse['status_code'] = 400;
        $reponse['status_message'] = 'La syntaxe de la requête est erronée.';
        $reponse['data'] = null;
        return $reponse;
    }
    $req = $linkpdo->prepare($sql);
    $req->bindParam(':id', $id, PDO::PARAM_INT);

    if (!$req->execute()) {
        $reponse['status_code'] = 404;
        $reponse['status_message'] = 'Ressource non trouvée.';
        $reponse['data'] = null;
        return $reponse;
    }
    $reponse['status_code'] = 200;
    $reponse['status_message'] = 'Succes';
    $reponse['data'] = $req->fetch();;
    return $reponse;
}

function readMedecin($linkpdo)
{
    $sql = "SELECT * FROM medecin";
    $reponse[] = null;
    if ($sql == false) {
        $reponse['status_code'] = 400;
        $reponse['status_message'] = 'La syntaxe de la requête est erronée.';
        $reponse['data'] = null;
        return $reponse;
    }
    $req = $linkpdo->prepare($sql);

    if (!$req->execute()) {
        $reponse['status_code'] = 404;
        $reponse['status_message'] = 'Ressource non trouvée.';
        $reponse['data'] = null;
        return $reponse;
    }
    $reponse['status_code'] = 200;
    $reponse['status_message'] = 'Succes';
    $reponse['data'] = $req->fetchAll();;
    return $reponse;
}

function createMedecin($linkpdo, $data){
    $sql = "INSERT INTO `medecin` (`id_medecin`, `civilite`, `nom`, `prenom`) VALUES
    (0, :civ , :nom, :prenom)";
    if ($sql == false) {
        $reponse['status_code'] = 400;
        $reponse['status_message'] = 'La syntaxe de la requête est erronée.';
        $reponse['data'] = null;
        return $reponse;
    }
    $req = $linkpdo->prepare($sql);
    $req->bindParam(':civ', $data['civilite'], PDO::PARAM_STR);
    $req->bindParam(':nom', $data['nom'], PDO::PARAM_STR);
    $req->bindParam(':prenom', $data['prenom'], PDO::PARAM_STR);

    $linkpdo->beginTransaction(); //Démarage de la transaction

    if (!$req->execute()) {
        $reponse['status_code'] = 404;
        $reponse['status_message'] = 'Ressource non trouvée.';
        $reponse['data'] = null;
        return $reponse;
    }

    $reponse['status_code'] = 200;
    $reponse['status_message'] = 'Succes';
    $reponse['data'] = $data;

    $linkpdo->commit();

    return $reponse;
}

function UpdateMedecin($linkpdo, $id, $data)
{
    $sqlRecup = "SELECT * FROM medecin WHERE id = :id";
    $reqRecup = $linkpdo->prepare($sqlRecup);
    $reqRecup->bindParam(':id', $id, PDO::PARAM_INT);
    $reqRecup->execute();
    $donneRecup = $reqRecup->fetch();
    $sql = "UPDATE `medecin` SET 
            `civilite` = :civ, 
            `nom` = :nom, 
            `prenom` = :prenom
        WHERE `id_medecin` = :id";
    if ($sql == false) {
        $reponse['status_code'] = 400;
        $reponse['status_message'] = 'La syntaxe de la requête est erronée.';
        $reponse['data'] = null;
        return $reponse;
    }
    $req = $linkpdo->prepare($sql);

    if ($data['civilite'] == null) {
        $req->bindParam(':civ', $donneRecup['civilite'], PDO::PARAM_STR);
    } else {
        $req->bindParam(':civ', $data['civilite'], PDO::PARAM_STR);
    }
    if ($data['nom'] == null) {
        $req->bindParam(':nom', $donneRecup['nom'], PDO::PARAM_INT);
    } else {
        $req->bindParam(':nom', $data['nom'], PDO::PARAM_INT);
    }
    if ($data['prenom'] == null) {
        $req->bindParam(':prenom', $donneRecup['prenom'], PDO::PARAM_STR);
    } else {
        $req->bindParam(':prenom', $data['prenom'], PDO::PARAM_STR);
    }
    $req->bindParam(':id', $id, PDO::PARAM_INT);

    $linkpdo->beginTransaction(); //Démarage de la transaction

    if (!$req->execute()) {
        $reponse['status_code'] = 404;
        $reponse['status_message'] = 'Ressource non trouvée.';
        $reponse['data'] = null;
        return $reponse;
    }

    $reponse['status_code'] = 200;
    $reponse['status_message'] = 'Succes';
    $reponse['data'] = $data;

    $linkpdo->commit();

    return $reponse;
}

function UpdateAllMedecin($linkpdo, $id, $data)
{
    $sql = "UPDATE `medecin` SET 
            `civilite` = :civ, 
            `nom` = :nom, 
            `prenom` = :prenom
        WHERE `id_medecin` = :id";
    if ($sql == false) {
        $reponse['status_code'] = 400;
        $reponse['status_message'] = 'La syntaxe de la requête est erronée.';
        $reponse['data'] = null;
        return $reponse;
    }

    $req = $linkpdo->prepare($sql);
    $req->bindParam(':civ', $data['civilite'], PDO::PARAM_STR);
    $req->bindParam(':nom', $data['nom'], PDO::PARAM_INT);
    $req->bindParam(':prenom', $data['prenom'], PDO::PARAM_STR);
    $req->bindParam(':id', $data['id_medecin'], PDO::PARAM_STR);

    $linkpdo->beginTransaction(); //Démarage de la transaction

    if (!$req->execute()) {
        $reponse['status_code'] = 404;
        $reponse['status_message'] = 'Ressource non trouvée.';
        $reponse['data'] = null;
        return $reponse;
    }

    $reponse['status_code'] = 200;
    $reponse['status_message'] = 'Succes';
    $reponse['data'] = $data;

    $linkpdo->commit();

    return $reponse;
}

function deleteMedecin($linkpdo, $id)
{
    $sql = "DELETE FROM `medecin` WHERE id = :id";
    if ($sql == false) {
        $reponse['status_code'] = 400;
        $reponse['status_message'] = 'La syntaxe de la requête est erronée.';
        $reponse['data'] = null;
        return $reponse;
    }
    $req = $linkpdo->prepare($sql);
    $req->bindParam(':id', $id, PDO::PARAM_STR);

    $linkpdo->beginTransaction(); //Démarage de la transaction

    if (!$req->execute()) {
        $reponse['status_code'] = 404;
        $reponse['status_message'] = 'Ressource non trouvée.';
        $reponse['data'] = null;
        return $reponse;
    }

    $reponse['status_code'] = 200;
    $reponse['status_message'] = 'Succes';
    $reponse['data'] = null;

    $linkpdo->commit();

    return $reponse;
}
