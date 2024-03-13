<?php
include "../../auth/connexionDB.php";
include "../functions/functions.php";
include "../../auth/jwt_utils.php";
if(is_jwt_valid(get_bearer_token(),'secret') == TRUE){
    $linkpdo = connexionBD();
    /// Identification du type de méthode HTTP envoyée par le client
    $http_method = $_SERVER['REQUEST_METHOD'];
    switch ($http_method){
        case "GET" :
            //Récupération des données dans l’URL si nécessaire
            if(!isset($_GET['id']))
            {
                $matchingData =affichConsult($linkpdo);
                deliver_response($matchingData['status_code'],$matchingData['status_message'],$matchingData['data']);
            }else{
                $id=htmlspecialchars($_GET['id']);
                $matchingData =readChuckFactSolo($linkpdo, $id);
                deliver_response($matchingData['status_code'],$matchingData['status_message'],$matchingData['data']);
            }
            break;
        case "POST" :
            //Récupération des données dans le corps
            if(!isset($_GET['id']))
            {
                $postedData = file_get_contents('php://input');
                $data = json_decode($postedData,true); //Reçoit du json et renvoi une
                $matchingData =createChuckFact($linkpdo,$data['phrase']);
                deliver_response($matchingData['status_code'],$matchingData['status_message'],$matchingData['data']);
            }
            break;
        case "PATCH":
            if(isset($_GET['id']))
            {
                $id=htmlspecialchars($_GET['id']);
                $postedData = file_get_contents('php://input');
                $data = json_decode($postedData,true); //Reçoit du json et renvoi une
                if (!isset($data['phrase'])) {
                    $data['phrase'] = null;
                }
                if (!isset($data['vote'])) {
                    $data['vote'] = null;
                }
                if (!isset($data['faute'])) {
                    $data['faute'] = null;
                }
                if (!isset($data['signalement'])) {
                    $data['signalement'] = null;
                }
                $matchingData =patchChuckFact($linkpdo,$id,$data['phrase'],$data['vote'],$data['faute'],$data['signalement']);
                deliver_response($matchingData['status_code'],$matchingData['status_message'],$matchingData['data']);

                //Traitement des données
            }
            break;
        case "PUT":
            if(isset($_GET['id']))
            {
                $id=htmlspecialchars($_GET['id']);
                $postedData = file_get_contents('php://input');
                $data = json_decode($postedData,true); //Reçoit du json et renvoi une
                $matchingData =putChuckFact($linkpdo,$id,$data['phrase'],$data['vote'],$data['faute'],$data['signalement']);
                deliver_response($matchingData['status_code'],$matchingData['status_message'],$matchingData['data']);

                //Traitement des données
            }
            break;
        case "DELETE":
            if(isset($_GET['id']))
            {
                $id=htmlspecialchars($_GET['id']);
                $matchingData =deleteChuckFact($linkpdo, $id);
                deliver_response($matchingData['status_code'],$matchingData['status_message'],$matchingData['data']);
                //Traitement des données
            }
            break;
    }
}else{
    deliver_response(401,"Utilisateur inconnue");

}


?>