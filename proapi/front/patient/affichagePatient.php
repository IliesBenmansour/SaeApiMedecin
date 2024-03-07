<?php
include '../functions/login.php';
?>
<head>
    <link rel="stylesheet" href="../style/styleAjoutPatient.css">
</head>

<header>
    <?php include "../header/headerAccueil.php" ?>
</header>
<main>
<h1>Liste de tous les patients</h1>
    <form class="input-group" method="POST">
        <?php
    include "../functions/formulaire.php";
    $form = new formulaire();
    echo $form->addElementInput("form-group", "Nom", "text", "inputNom", "nom", "Entrez le nom du patient",null);
    echo $form->addElementInput("form-group", "Prénom", "text", "inputPrenom", "prenom", "Entrez le prénom du patient",null);
    ?>
    <input class="btn_envoyer" type="submit" value="Envoyer">
    <input class="btn_reset" type="reset" value="Réinitialiser">
    </form>
    <?php
    include "../functions/functions.php";
    $bdd = connexionBD();
    $recherche = false;
    $patients = null;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $recherche = true;
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];

            $sqlRecherchePatient = "SELECT * FROM patient WHERE nom = :nom AND prenom = :prenom";
            $rqRecherchePatient = $bdd->prepare($sqlRecherchePatient);
            $rqRecherchePatient->bindParam(':nom', $nom, PDO::PARAM_STR);
            $rqRecherchePatient->bindParam(':prenom', $prenom, PDO::PARAM_STR);

            if(!$rqRecherchePatient->execute()) {
                die('Erreur à l\'exécution de la requête');
            }

            $patients = $rqRecherchePatient->fetchAll(PDO::FETCH_ASSOC);
            if(count($patients) == 0){
                echo "Aucun patient trouvé";
            }
    }

    if (!$recherche) {
        $rqPrendreToutLesPatient = "SELECT * FROM patient";
        $rqPrepare = $bdd->prepare($rqPrendreToutLesPatient);
        $rqPrepare->execute();
        $patients = $rqPrepare->fetchAll(PDO::FETCH_ASSOC);
    }
    ?>

    <table border="1">
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Sexe</th>
            <th>Adresse</th>
            <th>Code Postal</th>
            <th>Ville</th>
            <th>Date de Naissance</th>
            <th>Lieu de Naissance</th>
            <th>Numéro de Sécurité Sociale</th>
            <th>ID du Médecin</th>
            <th>Modifier</th>
            <th>Supprimer</th>
        </tr>
        <?php foreach ($patients as $patient): ?>
            <tr>
                <td><?php echo $patient['nom']; ?></td>
                <td><?php echo $patient['prenom']; ?></td>
                <td><?php echo $patient['sexe']; ?></td>
                <td><?php echo $patient['adresse']; ?></td>
                <td><?php echo $patient['cp']; ?></td>
                <td><?php echo $patient['ville']; ?></td>
                <td><?php echo $patient['date_naissance']; ?></td>
                <td><?php echo $patient['lieu_naissance']; ?></td>
                <td><?php echo $patient['numero_secu']; ?></td>
                <td><?php echo $patient['id_medecin']; ?></td>
                <td>
                    <a href="<?php echo "modifierPatient.php?nom=" . $patient['nom'] . "&prenom=" . $patient['prenom'] . "&adresse=" . $patient['adresse'] . "&cp=" . $patient['cp'] . "&ville=" . $patient['ville'] . "&date_naissance=" . $patient['date_naissance'] . "&lieu_naissance=" . $patient['lieu_naissance'] . "&numero_secu=" . $patient['numero_secu'] . "&id_medecin=" . $patient['id_medecin'] . "&sexe=" . $patient['sexe'] . "&id_patient=" . $patient['id_patient'] ?>">Modifier</a>
                </td>
                <td>
                    <a href="<?php echo "supprimerPatient.php?id_patient=" . $patient['id_patient'] ?>">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>
