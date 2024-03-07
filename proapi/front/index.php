<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="style/styleAjoutPatient.css">
    <title>Document</title>
</head>

<body>
<div>
    <form method="POST" action="">
        <div class="form-group">
            <label for="username" class="form-label">Nom d'utilisateur</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" name="Valider" class="btn btn-primary">Se connecter</button>
    </form>
</div>

<?php

require "functions/functions.php";

$bdd = connexionBD();

if (isset($_POST['Valider'])) {

    $login = $_POST['username']; // Modifier 'identifiant' à 'username'
    $mdp = $_POST['password'];   // Modifier 'mdp' à 'password'

    $reqRecupLogin = $bdd->prepare("SELECT * FROM users WHERE login = :login AND mdp = :mdp");
    $reqRecupLogin->bindParam(':login', $login, PDO::PARAM_STR);
    $reqRecupLogin->bindParam(':mdp', $mdp, PDO::PARAM_STR);

    if (!$reqRecupLogin->execute()) {
        echo '<div style="font-size: 18px; color: red;">Erreur dans la transmission de la requête</div>';
    }

    // Utilisation de fetch pour récupérer les résultats
    $resultat = $reqRecupLogin->fetch(PDO::FETCH_ASSOC);

    if ($resultat) {
        $_SESSION['login'] = $login;
        $_SESSION['mdp'] = $mdp;
        $_SESSION['isLog'] = true;
        header("Location: ./consultation/affichageConsultation.php");
        exit(); // N'oubliez pas d'ajouter cette ligne pour terminer le script après la redirection
    } else {
        unset($_SESSION['isLog']);
        unset($_SESSION['mdp']);
        unset($_SESSION['login']);
        echo 'Échec: identifiant ou mot de passe incorrect';
    }
}
?>
</body>

</html>
