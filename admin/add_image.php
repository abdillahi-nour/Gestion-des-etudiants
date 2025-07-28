<?php
include('../db/Db_project.php');

// Définir les messages par défaut
$errorMsg = "";
$successMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    try {
        $nom = $_FILES["image"]["name"];
        $type = $_FILES["image"]["type"];
        $size = $_FILES["image"]["size"];
        $bn_img = file_get_contents($_FILES["image"]["tmp_name"]);

        $sql = "INSERT INTO images (name, type, size, bn_img) VALUES (:nom, :type, :size, :bn_img)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindValue(':type', $type, PDO::PARAM_STR);
        $stmt->bindValue(':size', $size, PDO::PARAM_INT);
        $stmt->bindValue(':bn_img', $bn_img, PDO::PARAM_LOB);
        $stmt->execute();

        // Afficher un message de succès
        $successMsg = "L'image a été ajoutée avec succès.";
        header("Location: liste_images.php");
        exit(); // 
    } catch (PDOException $e) {
        // Capturer l'exception PDO et afficher le message d'erreur
        $errorMsg = "Erreur lors de l'ajout de l'image : " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>gestion image</title>
    <?php include 'allcss.php'; ?>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <div class="card mt-5">
        <div class="card-header">
            <h1 class="card-title">Gestion image</h1>
        </div>
        <div class="card-body py-3">
            <!-- Afficher les messages d'erreur et de succès -->
            <?php if ($errorMsg): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errorMsg; ?>
                </div>
            <?php endif; ?>
            <?php if ($successMsg): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $successMsg; ?>
                </div>
            <?php endif; ?>
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="image">Choisir  :</label>
                    <input type="file" class="form-control-file" id="image" name="image" required>
                </div>
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        </div>
        <div class="card-footer">
            <a href="liste_images.php" class="btn btn-info">Consulter toutes les images</a>
                  <!-- Bouton de retour -->
         <a href="home.php" class="btn btn-secondary">Retour</a>

        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
