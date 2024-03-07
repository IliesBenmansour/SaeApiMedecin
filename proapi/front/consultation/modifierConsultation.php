<?php

include '../functions/login.php';
include '../header/headerAccueil.php';
include "../functions/functions.php";

$bdd = connexionBD();

$rqMedecin = $bdd->prepare("SELECT nom, id_medecin FROM medecin");
$rqMedecin->execute();
$medecin = $rqMedecin->fetchAll(PDO::FETCH_ASSOC);

$rqPatient = $bdd->prepare("SELECT nom, id_patient FROM patient");
$rqPatient->execute();
$patient = $rqPatient->fetchAll(PDO::FETCH_ASSOC);
$ancienne_horaire = $_GET["date_heure"];
$separerHoraire = explode(' ', $ancienne_horaire);
$date = $separerHoraire[0];
$heure = $separerHoraire[1];
?>

<html>
<head>
    <link rel="stylesheet" href="../style/styleAjoutPatient.css">
</head>

<body>
<header>   <?php include "../header/headerAccueil.php" ?></header>

<main>
    <h1>Formulaire de Rendez-Vous</h1>
    <form action="" method="post">
        <label for="patient">Patient :</label>
        <select id="patient" name="nom_patient">
            <option value=""><?php echo $_GET['id_patient']?></option>
            <?php
            foreach ($patient as $patients) {
                echo "<option value='" . $patients['id_patient'] . "'>" . $patients['id_patient'] . "</option>";
            }
            ?>
        </select><br><br>

        <label for="medecin">Médecin :</label>
        <select id="medecin" name="nom_medecin">
            <option value=""><?php echo $_GET['id_medecin']?></option>
            <?php
            foreach ($medecin as $medecins) {
                echo "<option value='" . $medecins['id_medecin'] . "'>" . $medecins['id_medecin'] . "</option>";
            }
            ?>
        </select><br><br>

        <label for="date_rdv">Date du rendez-vous:</label>
        <input type="date" id="date_rdv" name="date_rdv" value="<?php echo $date?>" required><br><br>

        <label for="heure_rdv">Heure du rendez-vous:</label>
        <select id="heure_rdv" name="heure_rdv">
            <option value=""><?php echo $heure;?></option>
            <?php
            for ($heure = 9; $heure < 17; $heure++) {
                // pour les minutes
                foreach (array('00', '30') as $minutes) {
                    $heure_actuelle = $heure + ($minutes == '30' ? 0.5 : 0); // Heure actuelle en format num
                    $heure_debut = 12;
                    $heure_fin = 14;

                    $disabled = ($heure_actuelle >= $heure_debut && $heure_actuelle < $heure_fin) ? 'disabled' : '';

                    echo "<option value='{$heure}:{$minutes}' {$disabled}>{$heure}:{$minutes} - " . ($heure + ($minutes == '30' ? 1 : 0)) . ":" . ($minutes == '00' ? '30' : '00') . "</option>";
                }
            }
            ?>
        </select><br><br>

        <input type="submit" value="Prendre RDV">
    </form>
</main>
</body>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date_rdv = $_POST["date_rdv"];
    $medecin = $_POST["nom_medecin"];
    $patient = $_POST["nom_patient"];
    $heure_rdv = $_POST["heure_rdv"];

    $separerHeure = explode(':', $heure_rdv); // sa sert a separer les heure et les minute
    $heure = $separerHeure[0];
    $minute = $separerHeure[1];

    $separerDate = explode('-', $date_rdv); // on separer les jours
    $annee = $separerDate[0];
    $mois = $separerDate[1];
    $jour = $separerDate[2];

    $horaire = new DateTime();
    $horaire->setTime($heure, $minute, 0); // on met a jour l'horaire
    $horaire->setDate($annee,$mois,$jour); // on met a jour a la date inserer
    $date = $horaire->format('Y-m-d H:i:s');

    $rqGetDate = $bdd->prepare("SELECT date_heure FROM consultation where id_medecin = :id_medecin AND id_patient = :id_patient");
    $rqGetDate->bindParam(':id_medecin', $_GET['id_medecin'], PDO::PARAM_STR);
    $rqGetDate->bindParam(':id_patient', $_GET['id_patient'], PDO::PARAM_STR);
    $rqGetDate->execute();
    $recupDate = $rqGetDate->fetch(PDO::FETCH_ASSOC);

    $rqUpdateRdv = $bdd->prepare("UPDATE CONSULTATION SET date_heure = :date_heure, id_medecin = :id_medecin, id_patient = :id_patient 
                    WHERE date_heure = :date_ancienne AND id_patient = :id_patient_ancien AND id_medecin = :id_medecin_ancien");
    $rqUpdateRdv->bindParam(':date_heure', $date, PDO::PARAM_STR);
    $rqUpdateRdv->bindParam(':id_medecin', $medecin, PDO::PARAM_STR);
    $rqUpdateRdv->bindParam(':id_patient', $patient, PDO::PARAM_STR);
    $rqUpdateRdv->bindParam(':date_ancienne',  $recupDate['date_heure'], PDO::PARAM_STR);
    $rqUpdateRdv->bindParam(':id_patient_ancien', $_GET['id_patient'], PDO::PARAM_STR);
    $rqUpdateRdv->bindParam(':id_medecin_ancien', $_GET['id_medecin'], PDO::PARAM_STR);
    $rqUpdateRdv->execute();

}
?>

</html>
