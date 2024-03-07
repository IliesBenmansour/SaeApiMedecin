<?php
include '../functions/login.php';
include "../functions/functions.php";
$bdd = connexionBD();
// Vérifiez si l'ID est passé via l'URL
if (isset($_GET['id_medecin'])) {
    $id_a_modifier = $_GET['id_medecin'];

    $req = $bdd->prepare('SELECT * FROM medecin WHERE id_medecin = :id');
    $req->execute(array('id' => $id_a_modifier));
    $data = $req->fetch();
    $nom = $data['nom'];
    $prenom = $data["prenom"];
    $civilite = $data["civilite"];
} else {
    echo '<p>Aucun ID spécifié pour la modification.</p>';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Formulaire de contact</title>
    <meta charset="UTF-8">
</head>
<body>

<header><?php include "../header/headerAccueil.php"; ?></header>

<main>
<h1>Formulaire de contact</h1>
<form method="post">
    <?php
    include('../functions/formulaire.php');
    $form = new formulaire();

    echo $form->addElementInput("form-group", "Nom", "text", "inputNom", "nom", "Entrez le nom du medecin",$nom);
    echo $form->addElementInput("form-group", "Prénom", "text", "inputPrenom", "prenom", "Entrez le prénom du medecin", $prenom);
    echo $form->addElementGroupButton("form-group", "radio-inline", "radio", "Homme", "civilite", "Femme", "sexe", "form-check-input", "form-check-label");
 ?>
    <br><br>

    <input class="btn_envoyer" type="submit" value="Envoyer">
    <input class="btn_reset" type="reset" value="Réinitialiser">
</form>

<?php
// Traiter la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialiser la requête de mise à jour
    $updateSQL = 'UPDATE medecin SET ';
    $updateValues = array();

    // Vérifier chaque champ individuellement et ajouter à la requête si rempli
    if (!empty($_POST['nom'])) {
        $updateSQL .= 'nom = ?, ';
        $updateValues[] = $_POST['nom'];
    }

    if (!empty($_POST['prenom'])) {
        $updateSQL .= 'prenom = ?, ';
        $updateValues[] = $_POST['prenom'];
    }

    if (!empty($_POST['civilite'])) {
        if($_POST['civilite'] == "Homme"){
            $sexe = "M";
        } else {
            $sexe = "F";
        }
        $updateSQL .= 'civilite = ?, ';
        $updateValues[] = $sexe;
    }

    // Vérifier si au moins un champ a été rempli
    if (!empty($updateValues)) {
        // Supprimer la virgule et l'espace en trop à la fin de la requête
        $updateSQL = rtrim($updateSQL, ', ');

        // Ajouter la clause WHERE pour cibler l'enregistrement spécifique
        $updateSQL .= ' WHERE id_medecin = ?';
        $updateValues[] = $id_a_modifier;

        // Exécuter la requête de mise à jour
        $stmt = $bdd->prepare($updateSQL);
        if ($stmt->execute($updateValues)) {
            echo '<div style="font-size: 24px; color: green;">Opération réussie! Le medecin a été modifier avec succès.</div>';
            return;
        } else {
            echo '<div style="font-size: 18px; color: red;">Erreur dans l\'insertion de la requête</div>';
        }
    }
}
?>
</main>
</body>
</html>