<?php
function connexionBD() {
    $servername = "127.0.0.1";
    $db = "projet";
    $login = "root";

    try {
        $bdd = new PDO("mysql:host=$servername;dbname=$db;charset=utf8", $login);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $bdd;
    } catch (PDOException $e) {
        die('La connexion a la base de donnée a échoué: ' . $e->getMessage());
    }
}

function calculerAge($dateNaissance) {
    $dateNaissance = new DateTime($dateNaissance);
    $dateActuelle = new DateTime('now');

    // Calcul de la diff
    $interval = $dateNaissance->diff($dateActuelle);

    $age = $interval->y; // Sert a mettre la difference directement en année
    return $age;
}

function compterParTrancheAge($tableau, $personne) {

    foreach ($personne as $personnes) {
        $age = calculerAge($personnes['dn']);

        if ($age < 25) {
            $tableau[0]++;
        } elseif ($age <= 50) {
            $tableau[1]++;
        } else {
            $tableau[2]++;
        }
    }

    return $tableau;
}


function checkPatientExistence($bdd, $nom, $prenom, $numero_secu)
{
    $rqRecuperationId = "SELECT count(*) FROM patient WHERE nom=:nom AND prenom=:prenom AND numero_secu=:numero_secu";
    $prepareNbPatient = $bdd->prepare($rqRecuperationId);
    $prepareNbPatient->bindParam(':nom', $nom, PDO::PARAM_STR);
    $prepareNbPatient->bindParam(':prenom', $prenom, PDO::PARAM_STR);
    $prepareNbPatient->bindParam(':numero_secu', $numero_secu, PDO::PARAM_STR);

    if (!$prepareNbPatient->execute()) {
        die('Erreur à l\'exécution de la requête');
    }

    $nbPatient = $prepareNbPatient->fetchColumn();
    return $nbPatient;
}
