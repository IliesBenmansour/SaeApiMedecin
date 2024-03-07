<?php
include '../functions/login.php';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $ancien_nom = $_GET['nom'];
    $ancien_prenom = $_GET['prenom'];
    $ancien_adresse = $_GET['adresse'];
    $ancien_cp = $_GET['cp'];
    $ancien_ville = $_GET['ville'];
    $ancien_date_naissance = $_GET['date_naissance'];
    $ancien_lieu_naissance = $_GET['lieu_naissance'];
    $ancien_numero_secu = $_GET['numero_secu'];
    $ancien_id_medecin = $_GET['id_medecin'];
}


?>

    <head>
        <link rel="stylesheet" href="../style/styleAjoutPatient.css">
    </head>
<header>
    <?php include '../header/headerAccueil.php'; ?>
</header>


<main>
    <h1>Modifier le patient</h1>

    <form action="ajoutPatient.php" method="post">
<?php
        include('../functions/formulaire.php');
        $form = new formulaire();
        echo $form->addElementGroupButton("form-group", "radio-inline", "radio", "Homme", "sexe", "Femme", "sexe", "form-check-input", "form-check-label");
        echo $form->addElementInput("form-group", "Nom", "text", "inputNom", "nom",null, $ancien_nom);
        echo $form->addElementInput("form-group", "Prénom", "text", "inputPrenom", "prenom", null, $ancien_prenom);
        echo $form->addElementInput("form-group", "Adresse", "text", "inputAdresse", "adresse", null, $ancien_adresse);
        echo $form->addElementInput("form-group col-md-6", "Code Postal", "text", "inputCP", "cp",null,  $ancien_cp);
        echo $form->addElementInput("form-group col-md-6", "Ville", "text", "inputVille", "ville",null,  $ancien_ville);
        echo $form->addElementInput("form-group", "Date de Naissance", "date", "inputDateNaissance", "date_naissance",null,  $ancien_date_naissance);
        echo $form->addElementInput("form-group", "Lieu de Naissance", "text", "inputLieuNaissance", "lieu_naissance",null,  $ancien_lieu_naissance);
        echo $form->addElementInput("form-group", "Numéro de Sécurité Sociale", "text", "inputNumeroSecu", "numero_secu",null,  $ancien_numero_secu);
        echo $form->addElementInput("form-group", "ID du Médecin", "text", "inputMedecin", "id_medecin",null,        $ancien_id_medecin);
?>
        <button type="submit" class="btn btn-primary" value="Modifier" name="ok">Modifier Patient</button>
    </form>
</main>
<?php
include "../functions/functions.php";

$bdd = connexionBD();

if (isset($_POST['ok']) && $_POST['ok'] == 'Modifier') {

    $id = $_GET['id_patient'];

    //recupere les modification du formulaire
    $nouveau_nom = $_POST['nom'];
    $nouveau_prenom = $_POST['prenom'];
    $nouveau_adresse = $_POST['adresse'];
    $nouveau_cp = $_POST['cp'];
    $nouveau_ville = $_POST['ville'];
    $nouveau_date_naissance = $_POST['date_naissance'];
    $nouveau_lieu_naissance = $_POST['lieu_naissance'];
    $nouveau_numero_secu = $_POST['numero_secu'];
    $nouveau_id_medecin = $_POST['id_medecin'];
    $sexe = $_POST['sexe'];

    $nbPatient = checkPatientExistence($bdd, $nouveau_nom, $nouveau_prenom, $nouveau_numero_secu);

//Si le contact n'existe pas
    if ($nbPatient == 0) {


        //on fait cela car le UPDATE ici ne nous fait pas la mise a jour mais ajoute seulement une nouvelle ligne
        //ducoup ce bug ne nous permet pas de le faire avec UPDATE
        $req2 = $bdd->prepare('DELETE FROM consultation WHERE id_patient = :id');
        $req2->bindParam(':id', $id_patient, PDO::PARAM_INT);
        if(!$req2->execute()) {
            echo "La requette des rendez vous supprimer n'a pas bien etais executé";
        }

        $sqlSupprimer = "DELETE FROM patient WHERE id_patient = :id_patient";

        $requetteSupprimer = $bdd->prepare($sqlSupprimer);
        $requetteSupprimer->bindParam(':id_patient', $id_patient, PDO::PARAM_INT);

        if(!$requetteSupprimer->execute()){
            die("Erreur dans la requette pour supprimer");
        }

        $sqlMAJ = "UPDATE patient SET nom=:nom, prenom=:prenom, adresse=:adresse,sexe=:sexe ,cp=:cp, ville=:ville, date_naissance=:date_naissance, lieu_naissance=:lieu_naissance, numero_secu=:numero_secu, id_medecin=:id_medecin WHERE id_patient=:id";

        $rqInsertion = "INSERT INTO patient (nom, prenom, adresse, cp, ville, date_naissance, lieu_naissance, numero_secu, id_medecin,sexe) 
                        VALUES (:nom, :prenom, :adresse, :cp, :ville, :date_naissance, :lieu_naissance, :numero_secu, :id_medecin, :sexe)";

        $insertRq = $bdd->prepare($rqInsertion);
        $insertRq->bindParam(':nom', $nouveau_nom, PDO::PARAM_STR);
        $insertRq->bindParam(':prenom', $nouveau_prenom, PDO::PARAM_STR);
        $insertRq->bindParam(':adresse', $nouveau_adresse, PDO::PARAM_STR);
        $insertRq->bindParam(':cp', $nouveau_cp, PDO::PARAM_STR);
        $insertRq->bindParam(':ville', $nouveau_ville, PDO::PARAM_STR);
        $insertRq->bindParam(':date_naissance', $nouveau_date_naissance, PDO::PARAM_STR);
        $insertRq->bindParam(':lieu_naissance', $nouveau_lieu_naissance, PDO::PARAM_STR);
        $insertRq->bindParam(':numero_secu', $nouveau_numero_secu, PDO::PARAM_STR);
        $insertRq->bindParam(':id_medecin', $nouveau_id_medecin, PDO::PARAM_INT);
        $insertRq->bindParam(':sexe', $sexe , PDO::PARAM_STR);

        if ($insertRq->execute()) {
            // si la req s'execute
            echo '<div style="font-size: 24px; color: green;">Opération réussie! Le patient a été ajouté avec succès.</div>';
            return;
        } else {
            echo '<div style="font-size: 18px; color: red;">Erreur dans la mise a jour de la requête</div>';
        }
    } else {
        echo '<div style="font-size: 18px; color: red;">Le patient existe déjà</div>';
    }
}
?>