<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moyennes des Étudiants</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php include 'allcss.php'; ?>
   
</head>
<body>
<?php include 'navbar.php'; ?>
    <div class="container">
        
        <h1>Statistiques des Moyennes des Étudiants</h1>
        <canvas id="myChart" width="350" height="130"></canvas>
        <div id="etudiants"></div>
    </div>
          <!-- Bouton de retour -->
          <a href="home.php" class="btn btn-secondary">Retour</a>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php
            // Connexion à la base de données
              require_once '../db/Db_project.php';

            try {
              
                // Récupération des données des étudiants depuis la base de données
                $stmt = $conn->query('SELECT nom, moyenne FROM etudiants');
                $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Convertir les données PHP en données JavaScript
                echo 'const etudiants = ' . json_encode($etudiants) . ';';
            } catch(PDOException $e) {
                echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
            }
            ?>

            // Création du graphique
            const noms = [];
            const moyennes = [];
            const couleurs = [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)'
            ];

            etudiants.forEach((etudiant, index) => {
                noms.push(etudiant.nom);
                moyennes.push(etudiant.moyenne);
            });

            const ctx = document.getElementById('myChart').getContext('2d');
            const myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: noms,
                    datasets: [{
                        label: 'Moyenne',
                        data: moyennes,
                        backgroundColor: couleurs,
                        borderWidth: 2
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            
        });
    </script>

    <?php include 'footer.php'; ?>
</body>
</html>
