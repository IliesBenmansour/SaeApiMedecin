    <?php
    include '../functions/login.php';
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Traitement du formulaire</title>
        <meta charset="UTF-8">

    </head>
    <body>

    <header><?php include ('../header/headerAccueil.php') ?></header>

    <main>
    <h1>Ajouter un médecin</h1>

        <form method="POST" action="ajoutMedecin.php">
            <?php
            include('../functions/formulaire.php');
            $form = new formulaire();
            echo $form->addElementGroupButton("form-group", "radio-inline", "radio", "Homme", "civilite", "Femme", "sexe", "form-check-input", "form-check-label");
            echo $form->addElementInput("form-group", "Nom", "text", "inputNom", "nom", "Entrez le nom du medecin", null);
            echo $form->addElementInput("form-group", "Prénom", "text", "inputPrenom", "prenom", "Entrez le prénom du medecin", null);
            ?>
            <input class="btn_envoyer" type="submit" value="Envoyer" name="envoyer">
            <input class="btn_reset" type="reset" value="Réinitialiser">
        </form>



    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST['envoyer'])) {
            include('../functions/functions.php');

            $bdd = connexionBD();

            $nom = $_POST["nom"];
            $prenom = $_POST["prenom"];
            $civilite = $_POST["civilite"];
            $req = $bdd->prepare('SELECT * FROM medecin WHERE nom = :nom AND prenom = :prenom');
            $req->execute(array('nom' => $nom, 'prenom' => $prenom));

            if($civilite == "Homme"){
                $civilite = "M";
            } else {
                $civilite = "F";
            }
            $nbMedecin = $req->fetchColumn();

            if ($nbMedecin >0) {
                echo $nom;
                echo "La personne existe déjà dans la base de données.";
            } else {
                echo $prenom;
                $req = $bdd->prepare('INSERT INTO medecin (id_medecin, nom, prenom, civilite) VALUES (0, :nom, :prenom, :civilite)');
                $req->bindParam(':nom', $nom, PDO::PARAM_STR);
                $req->bindParam(':prenom', $prenom, PDO::PARAM_STR);
                $req->bindParam(':civilite', $civilite, PDO::PARAM_STR);
                if($req->execute()){
                    echo '<div style="font-size: 24px; color: green;">Opération réussie! Le medecin a été ajouté avec succès.</div>';
                } else {
                    echo '<div style="font-size: 24px; color: #d20000;">Erreur avec la requete d\'insertion</div>';
                }
            }

            echo '<table border="1">';
            echo '<tr><th>Nom</th><th>Prénom</th><th>Civilité</th></tr>';

            echo '<tr>';
            echo '<td>' . htmlspecialchars($_POST['nom']) . '</td>';
            echo '<td>' . htmlspecialchars($_POST['prenom']) . '</td>';
            echo '<td>' . htmlspecialchars($_POST['civilite']) . '</td>';
            echo '</tr>';

            echo '</table>';
        }
    }
    ?>
    </main>
    </body>
    </html>