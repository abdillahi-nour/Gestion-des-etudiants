<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admission des étudiants</title>
    <?php include 'allcss.php'; ?>
    <style>
        .table-custom {
            background-color: #f8f9fa;
            font-family: 'Courier New', Courier, monospace;
        }

        .table-custom th,
        .table-custom td {
            border-color: #dee2e6;
            font-size: 18px; 
        }

        .table-custom th {
            background-color: #007bff;
            color: #fff;
        }

        .table-custom tbody tr:hover {
            background-color: #e9ecef;
        }

        /* Styles pour les colonnes */
        .matricule-column {
            width: 10%;
        }

        .nom-column {
            width: 20%;
        }

        /* Styles pour les lignes */
        .mention-très-bien {
            background-color: #28a745;
            color: #fff;
        }

        .mention-bien {
            background-color: #17a2b8;
            color: #fff;
        }

        .mention-assez-bien {
            background-color: #ffc107;
        }

        .mention-passable {
            background-color: #dc3545;
            color: #fff;
        }

        .valid-icon {
            color: green;
        }

        .invalid-icon {
            color: red;
        }

        .pagination {
            justify-content: center;
        }
        h3 {
            text-align: center;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
    
<div class="container">
    <br>
   
    <h3>Admission des étudiants</h3>
    <!-- Tableau Bootstrap -->
    <table class="table table-striped table-bordered table-custom">
        <thead class="thead-dark">
            <tr>
                <th class="matricule-column">Matricule</th>
                <th class="nom-column">Nom</th>
                <th>Module 1</th>
                <th>Module 2</th>
                <th>Moyenne</th>
                <th>Mention</th>
                <th>Validation</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Inclusion du fichier de connexion à la base de données
            require_once '../db/Db_project.php';

            // Nombre d'étudiants par page
            $records_per_page = 7; // Limite de 7 lignes par page

            // Récupérer le numéro de page actuel à partir de l'URL
            $page = isset($_GET['page']) ? $_GET['page'] : 1;

            // Calculer l'offset pour la requête SQL
            $offset = ($page - 1) * $records_per_page;

            // Requête SQL pour sélectionner les étudiants avec pagination
            $sql = "SELECT * FROM etudiants LIMIT $offset, $records_per_page";

            // Exécution de la requête
            $result = $conn->query($sql);

            // Vérification si la requête a réussi
            if ($result) {
                // Boucle pour afficher les données des étudiants
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    echo '<td>' . 'CNE000' . $row['id'] . '</td>';
                    echo '<td>' . $row['nom'] . '</td>';
                    echo '<td>' . $row['note1'] . '</td>';
                    echo '<td>' . $row['note2'] . '</td>';
                    echo '<td>' . $row['moyenne'] . '</td>';

                    // Calculer la mention en fonction de la moyenne
                    $moyenne = $row['moyenne'];
                    $mention = '';

                    if ($moyenne >= 16) {
                        $mention = '<span class="mention-très-bien">Très bien</span>';
                    } elseif ($moyenne >= 14) {
                        $mention = '<span class="mention-bien">Bien</span>';
                    } elseif ($moyenne >= 12) {
                        $mention = '<span class="mention-assez-bien">Assez bien</span>';
                    } else {
                        $mention = '<span class="mention-passable">Passable</span>';
                    }

                    echo '<td>' . $mention . '</td>';

                    // Déterminer si l'étudiant est validé ou non validé en fonction de sa moyenne
                    $validation = $moyenne >= 10 ? '<span class="valid-icon">Valide</span>' : '<span class="invalid-icon">Non valide</span>';

                    echo '<td>' . $validation . '</td>';
                    echo '</tr>';
                }

                // Libérer la ressource de la requête
                $result->closeCursor();

                // Afficher les liens de pagination avec Bootstrap
                echo '<tr><td colspan="7"><nav><ul class="pagination justify-content-center">';
                $total_pages = ceil($conn->query("SELECT COUNT(*) FROM etudiants")->fetchColumn() / $records_per_page);
                for ($i = 1; $i <= $total_pages; $i++) {
                    echo '<li class="page-item ' . ($page == $i ? 'active' : '') . '"><a class="page-link" href="?page=' . $i . '">Page ' . $i . '</a></li>';
                }
                echo '</ul></nav></td></tr>';
            } else {
                // Affichage d'un message d'erreur si la requête a échoué
                echo "Erreur lors de l'exécution de la requête SQL";
            }
            ?>
        </tbody>
    </table>

</div>
<?php include '../all_component/footer.php'; ?>

</body>
</html>
