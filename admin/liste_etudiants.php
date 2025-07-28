<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Liste des étudiants</title>
    <?php include 'allcss.php'; ?>
</head>
<body>
<?php include 'navbar.php'; ?>
    
<div class="container">
   
    <h2>Liste des étudiants</h2>
    <!-- Tableau Bootstrap -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Ville</th>
                <th>Note 1</th>
                <th>Note 2</th>
                <th>Moyenne</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Inclusion du fichier de connexion à la base de données
            require_once '../db/Db_project.php';

            // Nombre d'étudiants par page
            $records_per_page = 10;

            // Récupérer le numéro de page actuel à partir de l'URL
            $page = isset($_GET['page']) ? $_GET['page'] : 1;

            // Calcul de  l'offset pour la requête SQL
            $offset = ($page - 1) * $records_per_page;

            // Requête SQL pour sélectionner les étudiants avec pagination
            $sql = "SELECT * FROM etudiants LIMIT $offset, $records_per_page";

            // Exécution de la requête
            $result = $conn->query($sql);

            // Vérification si la requête a réussi
            if ($result) {
                // Parcourir les résultats de la requête
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    echo '<td>' . $row['id'] . '</td>';
                    echo '<td>' . $row['nom'] . '</td>';
                    echo '<td>' . $row['login'] . '</td>';
                    echo '<td>' . $row['ville'] . '</td>';
                    echo '<td>' . $row['note1'] . '</td>';
                    echo '<td>' . $row['note2'] . '</td>';
                    echo '<td>' . $row['moyenne'] . '</td>';
                    
                    // Ajout des boutons "Supprimer" et "Modifier" pour chaque étudiant
                    echo '<td><a href="delete_etudiant.php?id=' . $row['id'] . '" class="btn btn-danger">Supprimer</a> <a href="update_etudiant.php?id=' . $row['id'] . '" class="btn btn-primary">Modifier</a></td>';
                    echo '</tr>';
                }

                // Libérer la ressource de la requête
                $result->closeCursor();
            } else {
                // Affichage d'un message d'erreur si la requête a échoué
                echo "Erreur lors de l'exécution de la requête SQL";
            }
            ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <ul class="pagination">
        <?php
        // Compter le nombre total d'étudiants
        $sql = "SELECT COUNT(*) AS total FROM etudiants";
        $result = $conn->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $total_records = $row['total'];

        // Calculer le nombre total de pages
        $total_pages = ceil($total_records / $records_per_page);

        // Afficher les liens de pagination
        for ($i = 1; $i <= $total_pages; $i++) {
            echo '<li class="page-item ' . ($page == $i ? 'active' : '') . '"><a class="page-link" href="liste_etudiants.php?page=' . $i . '">' . $i . '</a></li>';
        }
        ?>
    </ul>

    <!-- Bouton pour ajouter un nouvel étudiant -->
    <a href="add_etudiant.php" class="btn btn-success">Ajouter un étudiant</a>

    

    <!-- Bouton de retour -->
    <a href="home.php" class="btn btn-secondary">Retour</a>
</div>
<?php include '../all_component/footer.php'; ?>

</body>
</html>
