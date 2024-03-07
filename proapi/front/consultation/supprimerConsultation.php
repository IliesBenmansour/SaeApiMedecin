<?php
include '../functions/login.php';
?>
<head>
    <title>Suppression</title>
    <link rel="stylesheet" href="../style/styleAjoutPatient.css">
</head>

<form action="" method="POST">
    <h3>Voulez-vous confirmer supprmiez la consultaion</h3>
    <br>
    <input type="submit" value="Confirmer" name="oui">
    <input type="submit" value="Annuler" name="non">
</form>

<?php
include '../header/headerAccueil.php';
include "../functions/functions.php";

$bdd = connexionBD();

if (isset($_GET['date_heure'])){

    //Si il confirme la suppresion
    if(isset($_POST['oui'])){

        $sqlSupprimer = "DELETE FROM consultation WHERE date_heure = :date_heure AND id_medecin = :medecin";

        $requetteSupprimer = $bdd->prepare($sqlSupprimer);
        $requetteSupprimer->bindParam(':date_heure', $_GET['date_heure'], PDO::PARAM_STR);
        $requetteSupprimer->bindParam(':medecin', $_GET['medecin'], PDO::PARAM_INT);

        if(!$requetteSupprimer->execute()){
            die("Erreur dans la requette pour supprimer");
        }
        header('Location: affichageConsultation.php');
    }

}else {
    echo '<p>Aucun ID spécifié pour la suppression.</p>';
} ?>
