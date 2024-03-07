<?php
include '../functions/login.php';
include '../header/headerAccueil.php';

?>
<form action="" method="POST">
    <h3>Voulez-vous confirmer supprmiez l'utilisateur</h3>
    <br>
    <input type="submit" value="Confirmer" name="oui">
    <input type="submit" value="Annuler" name="non">
    <input type="hidden" name="formIdentifier" value="supprimerFormulaire">
</form>

<?php

include "../functions/functions.php";

$bdd = connexionBD();

$id_patient = $_GET['id_patient'];

if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    //Si il confirme la suppresion
    if (isset($_POST['oui']) && $_POST['oui'] == 'Confirmer'){

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
    }
    header('Location: ./affichagePatient.php');
    exit();
}