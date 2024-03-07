<?php
include 'jwt_utils.php';
include "functions.php";
include "connexionDB.php";
$linkpdo = connexionBD();
$http_method = $_SERVER['REQUEST_METHOD'];
$data = (array) json_decode(file_get_contents('php://input'), TRUE);


function isValidUser($login, $mdp){
    $linkpdo = connexionBD();
    $sql = $linkpdo->prepare('SELECT `login` FROM user WHERE `login`= :login');
    $sql->bindParam(':login',$login,PDO::PARAM_STR); //Attention au type du paramètre !
    if(!$sql->execute()){
        return false;
    }else{
        $sql = $linkpdo->prepare('SELECT `password` FROM user WHERE `login`= :login');
        $sql->bindParam(':login',$login,PDO::PARAM_STR); //Attention au type du paramètre !
        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        $password = $result['password'];
        if(password_verify($mdp,$password)){
            return true;
        }
        return false;
    }
}



if($http_method == "POST"){
    if(isValidUser($data['login'], $data['password'])){
        $login = $data['login'];

        $headers = array('alg' =>'HS256','typ'=>'JWT');
        $payload = array('login' => $login, 'exp' =>(time() +60));

        $jwt  = generate_jwt($headers, $payload,$secret = 'secret');

        $sql = $linkpdo->prepare('SELECT `role` FROM user WHERE `login`= :login');
        $sql->bindParam(':login',$login,PDO::PARAM_STR); //Attention au type du paramètre !
        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        $res = $result['role'];
        $data['jwt'] = $jwt;
        $data['role'] = $res;
        deliver_response(200,"tien le token",$data);
    }else{
        deliver_response(401,"Utilisateur inconnue");
    }
}
else{
    deliver_response(401,"Il faut utiliser POST");
}
?>