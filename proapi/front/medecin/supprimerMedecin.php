<?php
include '../functions/login.php';
include "../functions/functions.php";
$bdd = connexionBD();

// Vérifiez si l'ID est passé via l'URL
if (isset($_GET['id_medecin'])) {
    $id_a_supprimer = $_GET['id_medecin'];


    // violation primary key         $requetteSupprimer->bindParam(':id_patient', $id_patient, PDO::PARAM_INT);
    $req1 = $bdd->prepare('UPDATE patient SET id_medecin = NULL WHERE id_medecin = :id');
    $req1->bindParam(':id', $id_a_supprimer, PDO::PARAM_INT);
    $req1->execute();

    $req2 = $bdd->prepare('DELETE FROM consultation WHERE id_medecin = :id');
    $req2->bindParam(':id', $id_a_supprimer, PDO::PARAM_INT);
    if(!$req2->execute()) {
        echo "La requette des rendez vous supprimer n'a pas bien etais executé";
        return;
    }

    $req = $bdd->prepare('DELETE FROM medecin WHERE id_medecin = :id');
    $req->bindParam(':id', $id_a_supprimer, PDO::PARAM_INT);

    if ($req->execute()) {
        echo '<p>L\'élément avec l\'ID ' . htmlspecialchars($id_a_supprimer) . ' a été supprimé.</p>';
    } else{
        echo "Erreur lors de la suppression . $id_a_supprimer";
        return;
    }
} else {
    echo "Aucun ID spécifié pour la suppression";
}
?>
