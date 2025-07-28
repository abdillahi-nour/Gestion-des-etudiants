<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin User Page - E-Project</title>
    <?php include 'allcss.php'; ?>

    <style>
        a{
            text-decoration: none;
            color: black;
        }
        a:hover{
            text-decoration: none;
        }
        .crd-ho:hover{
            background-color: #f0f1f2;
        }
    </style>
</head>
<body style="background-color: #f0f1f2">
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-3">
        <h1 class="text-center">Bonjour, <?php echo isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : 'Admin'; ?></h1>

        <div class="row p-5">
            <!-- Section "Ajouter Etudiant" -->
            <div class="col-md-3">
                <a href="add_etudiant.php">
                    <div class="card crd-ho" style="height: 180px;">
                        <div class="card-body text-center">
                        <i class="fas fa-user-graduate fa-3x text-danger"></i>  
                            <h4>Ajouter Etudiant</h4>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Section "Liste des étudiants" -->
            <div class="col-md-3">
                <a href="liste_etudiants.php">
                    <div class="card crd-ho" style="height: 180px;">
                        <div class="card-body text-center">
                        <i class="fas fa-users fa-3x text-danger"></i>
                            <h4>Liste des étudiants</h4>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Section "Géo localisation" -->
            <div class="col-md-3">
                <a href="#">
                    <div class="card crd-ho" style="height: 180px;">
                        <div class="card-body text-center">
                        <i class="fas fa-print fa-4x text-primary"></i>
                            <h4>Exporter </h4>
                        </div>
                    </div>
                </a>
            </div>

             <!-- Section "note d examen" -->
             <div class="col-md-3">
                <a href="admission.php">
                    <div class="card crd-ho" style="height: 180px;">
                        <div class="card-body text-center">
                        <i class="fas fa-graduation-cap fa-4x fa-sm text-info"></i>
                            <h4>Admission</h4>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
      <!-- Bouton de retour -->
      <a href="home.php" class="btn btn-secondary">Retour</a>



    <?php include 'footer.php'; ?>
</body>
</html>
