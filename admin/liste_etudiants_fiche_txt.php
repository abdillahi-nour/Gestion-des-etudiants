<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des étudiants</title>
    <?php include 'allcss.php'; ?>
</head>
<body>
<?php include 'navbar.php'; ?>
<br>
<?php
    // Ouverture du fichier en mode lecture
    $fichier = fopen('master_rsi2020.txt', 'r');
    if ($fichier) {
        // Création du tableau HTML avec les classes Bootstrap
        echo '<div class="container">';
        echo '<table class="table table-bordered table-striped">';
        echo '<thead class="table-dark"><tr><th scope="col">Nom</th><th scope="col">Prénom</th><th scope="col">CNE</th><th scope="col">Module 1</th><th scope="col">Module 2</th><th scope="col">Module 3</th><th scope="col">Moyenne</th><th scope="col">Actions</th></tr></thead>';
        echo '<tbody>';

        // Lecture du contenu du fichier ligne par ligne
        while (($ligne = fgets($fichier)) !== false) {
            // Si la ligne est vide, on passe à la suivante
            if (trim($ligne) === '') {
                continue;
            }

            // Extraction des données de chaque ligne
            $donnees = explode(', ', $ligne);

            // Affichage des données dans une ligne du tableau
            echo '<tr>';
            foreach ($donnees as $donnee) {
                list($label, $valeur) = explode(': ', $donnee);
                echo '<td>' . $valeur . '</td>';
            }
            // Ajout des liens "Supprimer" et "Modifier"
            echo '<td><a href="supprimer.php?cne=' . $donnees[2] . '" class="btn btn-danger">Supprimer</a> | <a href="modifier.php?cne=' . $donnees[2] . '" class="btn btn-primary">Modifier</a></td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '</div>';

        // Fermeture du fichier
        fclose($fichier);
    } else {
        // Affichage d'un message d'erreur si le fichier n'existe pas
        echo 'Aucun fichier n\'a été trouvé.';
    }
?>
<?php include 'footer.php'; ?>
</body>
</html>
