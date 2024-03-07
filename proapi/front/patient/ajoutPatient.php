<?php
include '../functions/login.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/styleAjoutPatient.css">
    <title>Ajout de Patient</title>
</head>
<body>
<header>
    <?php include '../header/headerAccueil.php'; ?>
</header>
<main>

    <h1>Ajouter un patient</h1>
<form method="post" action="ajoutPatient.php">
    <?php
    include "../functions/formulaire.php";
    $form = new formulaire();
    echo $form->addElementGroupButton("form-group", "radio-inline", "radio", "Homme", "sexe", "Femme", "sexe", "form-check-input", "form-check-label");
    echo $form->addElementInput("form-group", "Nom", "text", "inputNom", "nom", "Entrez le nom du patient",null);
    echo $form->addElementInput("form-group", "Prénom", "text", "inputPrenom", "prenom", "Entrez le prénom du patient",null);
    echo $form->addElementInput("form-group", "Adresse", "text", "inputAdresse", "adresse", "123 rue du chemin",null);
    echo $form->addElementInput("form-group col-md-6", "Code Postal", "text", "inputCP", "cp", "Entrez le code postal",null);
    echo $form->addElementInput("form-group col-md-6", "Ville", "text", "inputVille", "ville", "Entrez la ville",null);
    echo $form->addElementInput("form-group", "Date de Naissance", "date", "inputDateNaissance", "date_naissance", "",null);
    echo $form->addElementInput("form-group", "Lieu de Naissance", "text", "inputLieuNaissance", "lieu_naissance", "Entrez le lieu de naissance",null);
    echo $form->addElementInput("form-group", "Numéro de Sécurité Sociale", "text", "inputNumeroSecu", "numero_secu", "Entrez le numéro de sécurité sociale",null);
    echo $form->addElementInput("form-group", "ID du Médecin", "text", "inputMedecin", "id_medecin", "Entrez le ID du médecin",null);
?>
    <button type="submit" class="btn btn-primary">Ajouter Patient</button>
</form>
</main>
</body>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connexion au serveur MySQL
    include "../functions/functions.php";

    $bdd = connexionBD();

    $sexe = $_POST["sexe"];
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $adresse = $_POST["adresse"];
    $cp = $_POST["cp"];
    $ville = $_POST["ville"];
    $date_naissance = $_POST["date_naissance"];
    $lieu_naissance = $_POST["lieu_naissance"];
    $numero_secu = $_POST["numero_secu"];
    $id_medecin = $_POST["id_medecin"];

    if($sexe == "Homme"){
        $sexe = "M";
    } else {
        $sexe = "F";
    }

    $nbPatient = checkPatientExistence($bdd, $nom, $prenom, $numero_secu);

    if ($nbPatient == 0) {
        $rqInsertion = "INSERT INTO patient (nom, prenom, adresse, cp, ville, date_naissance, lieu_naissance, numero_secu, id_medecin,sexe) 
                        VALUES (:nom, :prenom, :adresse, :cp, :ville, :date_naissance, :lieu_naissance, :numero_secu, :id_medecin, :sexe)";

        $insertRq = $bdd->prepare($rqInsertion);
        $insertRq->bindParam(':nom', $nom, PDO::PARAM_STR);
        $insertRq->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $insertRq->bindParam(':adresse', $adresse, PDO::PARAM_STR);
        $insertRq->bindParam(':cp', $cp, PDO::PARAM_STR);
        $insertRq->bindParam(':ville', $ville, PDO::PARAM_STR);
        $insertRq->bindParam(':date_naissance', $date_naissance, PDO::PARAM_STR);
        $insertRq->bindParam(':lieu_naissance', $lieu_naissance, PDO::PARAM_STR);
        $insertRq->bindParam(':numero_secu', $numero_secu, PDO::PARAM_STR);
        $insertRq->bindParam(':id_medecin', $id_medecin, PDO::PARAM_INT);
        $insertRq->bindParam(':sexe', $sexe , PDO::PARAM_STR);

        if ($insertRq->execute()) {
            // si la req s'execute
            echo '<div style="font-size: 24px; color: green;">Opération réussie! Le patient a été ajouté avec succès.</div>';
            return;
        } else {
            echo '<div style="font-size: 18px; color: red;">Erreur dans l\'insertion de la requête</div>';
        }
    } else {
        echo '<div style="font-size: 18px; color: red;">Le patient existe déjà</div>';
    }
}
?>