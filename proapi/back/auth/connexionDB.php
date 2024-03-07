<?php
// Connexion au serveur MySQL
function connexionBD() {
try {
    $linkpdo = new PDO("mysql:host=localhost;dbname=r401_api;charset=utf8", "root", "");
    $linkpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $linkpdo;
} catch (PDOException $e) {
    die('La connexion a la base de donnée a échoué: ' . $e->getMessage());
}
}
?>