<?php
include '../functions/login.php';
include "../functions/functions.php";

$bdd = connexionBD();

$rqMedecin = "SELECT id_medecin,nom FROM medecin";
$resultRqMedecin = $bdd-> query($rqMedecin);
$medecins = $resultRqMedecin->fetchAll();

$rqRecuperationRDV = "SELECT c.date_heure,c.duree,m.nom  as Medecin ,c.id_medecin, p.nom as Patient , p.id_patient FROM consultation c,medecin m,patient p 
                      WHERE c.id_medecin = m.id_medecin 
                      AND p.id_patient = c.id_patient
                      ORDER BY c.date_heure DESC";
$resultRqRecuperationRDV = $bdd->query($rqRecuperationRDV);
$consultations = $resultRqRecuperationRDV->fetchAll(PDO::FETCH_ASSOC); ?>

<head>
    <meta charset="UTF-8">
    <title>Affichage des Consultations</title>
    <link rel="stylesheet" href="../style/styleAjoutPatient.css">
</head>
<body>
<header>
    <?php include '../header/headerAccueil.php'; ?>
</header>
<main>
<h1>Liste des Consultations</h1>
    <form action="" method="GET">
        <div class="filtre">
            <label for="filtre_medecin">Choisissez un médecin :</label>
            <select name="medecin" id="filtre_medecin">
                <option value="">--Tous les médecins--</option>
                <?php foreach ($medecins as $medecin): ?>
                    <option  value="<?php echo $medecin['id_medecin']; ?>" <?php if (isset($_GET['medecin']) && $_GET['medecin'] == $medecin['id_medecin']) echo 'selected'; ?>>
                        <?php echo $medecin['nom']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Filtrer</button>
        </div>
    </form>

<table>
    <tr>
        <th>Horaire</th>
        <th>Medecin</th>
        <th>Duree</th>
        <th>Patient</th>
        <th>Modifier</th>
        <th>Supprimer</th>
    </tr>
    <?php
    $rqRecuperationRDV2 = "SELECT c.date_heure,c.duree,m.nom  as Medecin ,c.id_medecin, p.nom as Patient , p.id_patient FROM consultation c,medecin m,patient p 
                          WHERE c.id_medecin = m.id_medecin 
                          AND p.id_patient = c.id_patient
                          AND c.id_medecin = :id_medecin
                          ORDER BY c.date_heure DESC";
    $resultRqRecuperationRDV2 = $bdd->prepare($rqRecuperationRDV2);
    $resultRqRecuperationRDV2->bindParam(':id_medecin', $_GET['medecin'], PDO::PARAM_STR);
    $resultRqRecuperationRDV2->execute(); // Ajoutez cette ligne
    $consultations2 = $resultRqRecuperationRDV2->fetchAll(PDO::FETCH_ASSOC);
    if (isset($_GET['medecin'])) {
            foreach ($consultations2 as $consultation2): ?>
                <tr>
                    <td> <?php echo $consultation2['date_heure'] ?> </td>
                    <td> <?php echo $consultation2['Medecin'] ?> </td>
                    <td><?php
                        if ($consultation2['duree'] >= 60) {
                            echo floor($consultation2['duree'] / 60) . 'h ' . ($consultation2['duree'] % 60) . 'min';
                        } else {
                            echo $consultation2['duree'] . ' min';
                        }
                        ?></td>
                    <td> <?php echo $consultation2['Patient'] ?> </td>
                    <td>
                        <a href="<?php echo "modifierConsultation.php?date_heure=" . $consultation2['date_heure'] . "&medecin=" . $consultation2['Medecin'] . "&duree=" . $consultation2['duree']. "&id_medecin=" . $consultation2['id_medecin']. "&id_patient=" . $consultation2['id_patient']. "&patient=" . $consultation2['Patient']  ?>">Modifier</a>
                    </td>
                    <td>
                        <a href="<?php echo "supprimerConsultation.php?date_heure=" . $consultation2['date_heure'] . "&medecin=" . $consultation2['id_medecin']?>">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?><?php
        }else{

    foreach ($consultations as $consultation): ?>
        <tr>
            <td> <?php echo $consultation['date_heure'] ?> </td>
            <td> <?php echo $consultation['Medecin'] ?> </td>
            <td><?php
            if ($consultation['duree'] >= 60) {
                echo floor($consultation['duree'] / 60) . 'h ' . ($consultation['duree'] % 60) . 'min';
            } else {
                echo $consultation['duree'] . ' min';
            }
            ?></td>
            <td> <?php echo $consultation['Patient'] ?> </td>
            <td>
                <a href="<?php echo "modifierConsultation.php?date_heure=" . $consultation['date_heure'] . "&medecin=" . $consultation['Medecin'] . "&duree=" . $consultation['duree']. "&id_medecin=" . $consultation['id_medecin']. "&id_patient=" . $consultation['id_patient']. "&patient=" . $consultation['Patient']  ?>">Modifier</a>
            </td>
            <td>
                <a href="<?php echo "supprimerConsultation.php?date_heure=" . $consultation['date_heure'] . "&medecin=" . $consultation['id_medecin']?>">Supprimer</a>
            </td>
        </tr>
    <?php endforeach;} ?>
</table>
</main>

