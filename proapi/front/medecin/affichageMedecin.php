<?php
include '../functions/login.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Formulaire de Medecin</title>
    <meta charset="UTF-8">

</head>
<body>

<header> <?php include "../header/headerAccueil.php"; ?></header>
<main>
<h1>Formulaire de Medecin</h1>
    <?php
    include "../functions/functions.php";
    $bdd = connexionBD();
    ?>

<form method="post">
    <?php
    include '../functions/formulaire.php';
    $form = new formulaire();
    echo $form->addElementInput("form-group", "Nom", "text", "inputNom", "nom", "Entrez le nom du medecin",null);
    echo $form->addElementInput("form-group", "Prénom", "text", "inputPrenom", "prenom", "Entrez le prénom du medecin",null);
?>

    <button type="submit" class="btn btn-primary">Envoyer</button>
    <button type="reset" class="btn btn-primary">Reinitialiser</button>
</form >
</main>
</body>
</html>

<?php
$autreVariableBool = false;
if (isset($_POST['nom'])) {
    $nom = $_POST["nom"];

    if ($_POST['prenom'] == "") {
        $req = $bdd->prepare('SELECT * FROM medecin WHERE nom = :nom');
        $req->execute(array('nom' => $nom));
        $autreVariableBool = true;
    } else {
        $prenom = $_POST["prenom"];
        $req = $bdd->prepare('SELECT * FROM medecin WHERE nom = :nom AND prenom = :prenom');
        $req->execute(array('nom' => $nom, 'prenom' => $prenom));
        $autreVariableBool = true;
    }


    if ($req->rowCount() > 0) {
        echo '<table border="1">';
        echo '<tr><th>ID</th><th>Nom</th><th>Prénom</th><th>Civilité</th><th>Supprimer</th><th>Modifier</th></tr>';

        while ($data = $req->fetch()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($data['id_medecin']) . '</td>';
            echo '<td>' . htmlspecialchars($data['nom']) . '</td>';
            echo '<td>' . htmlspecialchars($data['prenom']) . '</td>';
            echo '<td>' . htmlspecialchars($data['civilite']) . '</td>';
            echo '<td> <a href="supprimer.php?id_medecin=' .htmlspecialchars($data['id_medecin']) . '">Supprimer</a> </td>';
            echo '<td> <a href="modifier.php?id_medecin=' .$data['id_medecin']. '">Modifier</a> </td>';
            echo '</tr>';
        }

        echo '</table>';}
    else {
        echo "Aucun résultat trouvé.";
    }
}

$reqAllMedecins = $bdd->query('SELECT * FROM medecin');
if($autreVariableBool == false){
    echo '<table border="1">';
    echo '<tr><th>ID</th><th>Nom</th><th>Prénom</th><th>Civilité</th><th>Supprimer</th><th>Modifier</th>< </tr>';

    while ($data = $reqAllMedecins->fetch()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($data['id_medecin']) . '</td>';
        echo '<td>' . htmlspecialchars($data['nom']) . '</td>';
        echo '<td>' . htmlspecialchars($data['prenom']) . '</td>';
        echo '<td>' . htmlspecialchars($data['civilite']) . '</td>';
        echo '<td> <a href="supprimerMedecin.php?id_medecin=' .htmlspecialchars($data['id_medecin']) . '">Supprimer</a> </td>';
        echo '<td> <a href="modifierMedecin.php?id_medecin=' .htmlspecialchars($data['id_medecin']). '">Modifier</a> </td>';
        echo '</tr>';
    }
}
?>
