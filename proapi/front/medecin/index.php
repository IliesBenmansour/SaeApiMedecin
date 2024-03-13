<!DOCTYPE html>
<html>
<head>
    <title>Formulaire de Medecin</title>
    <meta charset="UTF-8">
</head>
<body>

<header> <?php include "../element/headerAccueil.php"; ?></header>
<main>
    <?php
    if($_SERVER['REQUEST_METHOD'] != null){

    }
    ?>

    <h1>Formulaire de Medecin</h1>

    <form method="post" action="controlleur.php">
        <?php
        include '../../back/api/functions/formulaire.php';
        $form = new formulaire();
        echo $form->addElementInput("form-group", "Nom", "text", "inputNom", "nom", "Entrez le nom du medecin", null);
        echo $form->addElementInput("form-group", "Prénom", "text", "inputPrenom", "prenom", "Entrez le prénom du medecin", null);
        ?>
        <button type="submit" class="btn btn-primary">Envoyer</button>
        <button type="reset" class="btn btn-primary">Reinitialiser</button>
    </form>
</main>
</body>
</html>
