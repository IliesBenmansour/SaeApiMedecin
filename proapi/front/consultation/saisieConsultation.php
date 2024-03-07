<?php
include '../functions/login.php';
include '../header/headerAccueil.php';
include "../functions/functions.php";

$bdd = connexionBD();

// requete prepare pour avoir tous les rdv des medecins pour mettre dans le select
$rqMedecin = $bdd->prepare("SELECT nom,id_medecin FROM medecin");
$rqMedecin->execute();
$medecin = $rqMedecin->fetchAll(PDO::FETCH_ASSOC);

$rqPatient = $bdd->prepare("SELECT nom, id_patient FROM patient");
$rqPatient->execute();
$patient = $rqPatient->fetchAll(PDO::FETCH_ASSOC);
?>

    <head>
        <link rel="stylesheet" href="../style/styleAjoutPatient.css">
    </head>

    <header> <?php include "../header/headerAccueil.php" ?></header>

    <main>
        <h1>Formulaire de Rendez-Vous</h1>
        <form action="" method="post">
            <label for="patient">Patient :</label>
            <select id="patient" name="nom_patient" required >
                <option value="">Choix du patient</option>
                <?php
                foreach ($patient as $patients) {
                    echo "<option value='" . $patients['id_patient'] . "'>" . $patients['id_patient'] . "</option>";
                }
                ?>
            </select><br><br>

            <label for="medecin">Médecin :</label>
            <select id="medecin" name="nom_medecin" required>
                <option value="">Choix du médecin</option>
                <?php
                foreach ($medecin as $medecins) {
                    echo "<option value='" . $medecins['id_medecin'] . "'>" . $medecins['id_medecin'] . "</option>";
                }
                ?>
            </select><br><br>

            <label for="date_rdv">Date du rendez-vous:</label>
            <input type="date" id="date_rdv" name="date_rdv" required><br><br>

            <label for="heure_rdv">Heure du rendez-vous:</label>
            <select id="heure_rdv" name="heure_rdv" required>
                <option value="">Choix d'une disponible</option>
                <?php
                for ($heure = 9; $heure < 17; $heure++) {
                    // pour les minutes
                    foreach (array('00', '30') as $minutes) {
                        $heure_actuelle = $heure + ($minutes == '30' ? 0.5 : 0); // Heure actuelle en format num
                        $heure_debut = 12;
                        $heure_fin = 14;

                        if ($heure_actuelle >= $heure_debut && $heure_actuelle < $heure_fin) {
                            echo "<option value='{$heure}:{$minutes}' disabled>{$heure}:{$minutes} - " . ($heure + ($minutes == '30' ? 1 : 0)) . ":" . ($minutes == '00' ? '30' : '00') . " (non reservable)</option>";
                        } else {
                            echo "<option value='{$heure}:{$minutes}'>{$heure}:{$minutes} - " . ($heure + ($minutes == '30' ? 1 : 0)) . ":" . ($minutes == '00' ? '30' : '00') . "</option>";
                        }
                    }
                }
                ?>
            </select><br><br>

            <input type="submit" value="Prendre RDV">
        </form>

    </main>

<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $date_rdv = $_POST["date_rdv"];
    $medecin = $_POST["nom_medecin"];
    $patient = $_POST["nom_patient"];
    $heures = $_POST["heure_rdv"];
    $duree = 30; // duree d'un rdv

    $separerHeure = explode(':', $heures); // sa sert a separer les heure et les minute
    $heure = $separerHeure[0];
    $minute = $separerHeure[1];

    $separerDate = explode('-', $date_rdv); // on separer les jours
    $annee = $separerDate[0];
    $mois = $separerDate[1];
    $jour = $separerDate[2];

    $horaire = new DateTime();
    $horaire->setTime($heure, $minute, 0); // on met a jour l'horaire
    $horaire->setDate($annee,$mois,$jour); // on met a jour a la date inserer

    $date = $horaire->format('Y-m-d H:i:s'); // on met la date final au format DateTime


    // requete prépare pour recup les rdv du medecin
    $rqMedecinMedecinRDV = "SELECT date_heure FROM consultation WHERE id_medecin = :id_medecin";
    $requetePrepareMedecinRDV = $bdd->prepare($rqMedecinMedecinRDV);
    $requetePrepareMedecinRDV->bindParam(':id_medecin',$medecin);
    $requetePrepareMedecinRDV->execute();
    $medecinRDV = $requetePrepareMedecinRDV->fetchAll(PDO::FETCH_ASSOC);

    $rdvReserver = false;
    foreach ($medecinRDV as $rdv){
        if($rdv["date_heure"] == $date){
            echo "Un rendez-vous est déja réserver a cette heure la";
            $rdvReserver = true;
            break;
        }
    }
    if($rdvReserver == false){
         $rqInsertion = "INSERT INTO consultation(id_medecin,date_heure,duree,id_patient) VALUES (:id_medecin,:date_heure,:duree,:id_patient)";
         $insertionRDV = $bdd->prepare($rqInsertion);

         $insertionRDV->bindParam(':id_medecin',$medecin);
         $insertionRDV->bindParam(':date_heure',$date );
         $insertionRDV->bindParam(':duree',$duree );
         $insertionRDV->bindParam(':id_patient',$patient );

         if($insertionRDV->execute()){
             echo "Le rendez-vous a bien etais enregistrée";
         } else {
             echo "Erreur dans l'insertion de la requête";
         }
    }
}

?>