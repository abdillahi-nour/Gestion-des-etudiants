<?php
    // Vérification si le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupération des données saisies
        $nom = htmlspecialchars($_POST['nom']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $cne = htmlspecialchars($_POST['cne']);
        $note_module_1 = floatval($_POST['note_module_1']);
        $note_module_2 = floatval($_POST['note_module_2']);
        $note_module_3 = floatval($_POST['note_module_3']);

        // Calcul de la moyenne et arrondissement à deux chiffres après la virgule
        $moyenne = round(($note_module_1 + $note_module_2 + $note_module_3) / 3, 2);

        // Formatage des données
        $contenu_fichier = "Nom: $nom, Prénom: $prenom, CNE: $cne, Module 1: $note_module_1, Module 2: $note_module_2, Module 3: $note_module_3, Moyenne: $moyenne\n";

        // Ouverture du fichier en mode append (ajout)
        $fichier = fopen('master_rsi2020.txt', 'a');
        if ($fichier) {
            // Ajout des données à la fin du fichier
            fwrite($fichier, $contenu_fichier);
            fclose($fichier);

            // Redirection vers la même page en utilisant la méthode POST
            header("Location: {$_SERVER['PHP_SELF']}");
            exit;
        } else {
            echo '<script>alert("Une erreur est survenue lors de l\'ajout des données.");</script>';
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de formulaire</title>
    <?php include 'allcss.php'; ?>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
<div class="row mt-2">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
            <h1 class="card-title text-center">Gestion de formulaire</h1>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="nom">Nom:</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>

                <div class="form-group">
                    <label for="prenom">Prénom:</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" required>
                </div>

                <div class="form-group">
                    <label for="cne">CNE:</label>
                    <input type="text" class="form-control" id="cne" name="cne" required>
                </div>

                <div class="form-group">
                    <label for="note_module_1">Note module 1:</label>
                    <input type="number" class="form-control" id="note_module_1" name="note_module_1" required>
                </div>

                <div class="form-group">
                    <label for="note_module_2">Note module 2:</label>
                    <input type="number" class="form-control" id="note_module_2" name="note_module_2" required>
                </div>

                <div class="form-group">
                    <label for="note_module_3">Note module 3:</label>
                    <input type="number" class="form-control" id="note_module_3" name="note_module_3" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-check"></i> Valider</button>
            </form>

            <?php
                // Affichage d'un message si les données ont été ajoutées avec succès
                if (isset($_GET['success']) && $_GET['success'] == 'true') {
                    echo '<div class="alert alert-success mt-3" role="alert">Les données ont été ajoutées avec succès.</div>';
                }
            ?>

            <button onclick="consulterListe()" class="btn btn-secondary btn-block mt-3"><i class="fas fa-list"></i> Consulter la liste</button>
        </div>
    </div>
</div>

<!-- Inclusion du script JavaScript Bootstrap -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
    function consulterListe() {
        window.open('liste_etudiants_fiche_txt.php', '_blank');
    }
</script>
<?php include 'footer.php'; ?>
</body>
</html>
