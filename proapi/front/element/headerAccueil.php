<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../styleAjoutPatient.css">
</head>

<nav>
    <div class="wrapper">
        <div class="logo"><a href="#">47 Médecine</a></div>
        <input type="radio" name="slider" id="menu-btn">
        <input type="radio" name="slider" id="close-btn">
        <ul class="nav-links">
            <label for="close-btn" class="btn close-btn"><i class="fas fa-times"></i></label>
            <li>
                <a href="#" class="desktop-item">Usager</a>
                <input type="checkbox" id="showDrop">
                <label for="showDrop" class="mobile-item">Usager</label>
                <ul class="drop-menu">
                    <li><a href="../patient/affichagePatient.php">Affichage</a></li>
                    <li><a href="../patient/ajoutPatient.php">Ajouter</a></li>
                </ul>
            </li>
            <li>
                <a href="#" class="desktop-item">Médecins</a>
                <input type="checkbox" id="showDrop">
                <label for="showDrop" class="mobile-item">Médecins</label>
                <ul class="drop-menu">
                    <li><a href="../medecin/affichageMedecin.php">Affichage</a></li>
                    <li><a href="../medecin/ajoutMedecin.php">Ajouter</a></li>
                </ul>
            </li>
            <li>
                <a href="#" class="desktop-item">Consultations</a>
                <input type="checkbox" id="showDrop">
                <label for="showDrop" class="mobile-item">Consultations</label>
                <ul class="drop-menu">
                    <li><a href="../consultation/affichageConsultation.php">Affichage</a></li>
                    <li><a href="../consultation/saisieConsultation.php">Saisie</a></li>
                </ul>
            </li>
            <li>
                <a class="desktop-item" href="../statistiques/TableauUsager.php">Statistiques</a>
                <input type="checkbox" id="showDrop">
            </li>
        </ul>
        <label for="menu-btn" class="btn menu-btn"><i class="fas fa-bars"></i></label>
    </div>
</nav>
</html>
