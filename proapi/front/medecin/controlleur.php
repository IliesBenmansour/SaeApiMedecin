<?php
include '../../back/api/functions/functions.php';
include '../../back/api/functions/jwt_utils.php';
include '../../back/api/medecin/index.php';

function method($linkpdo) {
    $bearer_token = get_bearer_token();
    $http_method = $_SERVER['REQUEST_METHOD'];

    switch ($http_method){
        case "GET" :
            if(isset($_GET['id_medecin'])) {
                $id = htmlspecialchars($_GET['id_medecin']);
                $matchingData = readMedecinID($id, $linkpdo);
            } else {
                $matchingData = readMedecin($linkpdo);
            }
            break;
        case "POST" :
            $postedData = file_get_contents('php://input');
            $data = json_decode($postedData, true);
            $matchingData = createMedecin($linkpdo, $data);
            break;
        case "PATCH" :
            if(isset($_GET['id_medecin'])) {
                $id = htmlspecialchars($_GET['id_medecin']);
                $postedData = file_get_contents('php://input');
                $data = json_decode($postedData, true);
                $matchingData = updateMedecin($linkpdo, $id, $data);
            }
            break;
        case "PUT" :
            if(isset($_GET['id_medecin'])) {
                $id = htmlspecialchars($_GET['id_medecin']);
                $postedData = file_get_contents('php://input');
                $data = json_decode($postedData, true);
                $matchingData = updateAllMedecin($linkpdo, $id, $data);
            }
            break;
        case "DELETE" :
            if(isset($_GET['id_medecin'])) {
                $id = htmlspecialchars($_GET['id_medecin']);
                $matchingData = deleteMedecin($linkpdo, $id);
            }
            break;
    }

    return $matchingData;
}

// Appel de la fonction principale
$result = method(connexionBD());

// Préparation de la réponse
$data = deliver_response($result['status_code'], $result['status_message'], $result['data']);

// Envoi de la réponse
echo json_encode($data);

?>
