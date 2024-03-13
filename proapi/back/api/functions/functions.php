<?php
function connexionBD() {
    $servername = "localhost";
    $db = "cabinet";
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

function deliver_response($status_code, $status_message, $reponse = null)
{
    // Paramétrage de l'entête HTTP
    http_response_code($status_code);

    // Pour autoriser le JS
    header("Access-Control-Allow-Origin: http://localhost/clientChuckJS_etu/");
    header("Access-Control-Allow-Methods: POST, GET, PATCH, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization   ");
    header("Content-Type:application/json; charset=utf-8");

    $response['status_code'] = $status_code;
    $response['status_message'] = $status_message;
    $response['data'] = $reponse;

    // Mapping de la réponse au format JSON
    $json_response = json_encode($response);

    if ($json_response === false) {
        die('json encode ERROR : ' . json_last_error_msg());
    }

    // Affichage de la réponse (Retourné au client)
 //   echo $json_response;

    return $json_response;
}

function roleUser($username, $password)
{
    $bdd = connexionBD();
    $reqRecupLogin = $bdd->prepare("SELECT role FROM user WHERE login = :login");
    $reqRecupLogin->bindParam(':login', $username, PDO::PARAM_STR);
    $reqRecupLogin->execute();

    return $reqRecupLogin->fetch(PDO::FETCH_ASSOC);
}

function isValidUser($username, $password)
{
    $bdd = connexionBD();
    $reqRecupLogin = $bdd->prepare("SELECT * FROM user WHERE login = :login");
    $reqRecupLogin->bindParam(':login', $username, PDO::PARAM_STR);
    $reqRecupLogin->execute();

    $resultat = $reqRecupLogin->fetch(PDO::FETCH_ASSOC);

    if ($resultat) {
        $hashedPassword = $resultat['password'];

        if (password_verify($password, $hashedPassword)) {
            return true;
        }
    }
    return false;
}