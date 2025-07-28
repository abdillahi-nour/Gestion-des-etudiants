<?php
include('../db/Db_project.php');
session_start();

// suppression d image
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM images WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    header("Location: liste_images.php");
    exit();
}

// Retrieve all images
$images = $conn->query("SELECT id, name, type, size, bn_img FROM images")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des images</title>
    <?php include 'allcss.php'; ?>
</head>
<body>
<?php include 'navbar.php'; ?>
    <div class="container">
        <h1 class="mt-5">Toutes les images</h1>
        <div class="row mt-2">
            <?php foreach ($images as $image): ?>
            <div class="col-md-3 mb-2">
                <div class="card">
                    <div class="card-body">
                        <!-- Ajout d'un tooltip pour afficher les informations -->
                        <img src="data:<?php echo htmlspecialchars($image['type']); ?>;base64,<?php echo base64_encode($image['bn_img']); ?>" class="card-img-top img-thumbnail" alt="..." 
                             style="max-width: 200px; max-height: 200px;" 
                             title="<?php echo htmlspecialchars($image['name']) . ' | ' . round($image['size'] / 1024, 2) . ' Ko | ' . htmlspecialchars($image['type']); ?>">
                        <h5 class="card-title"><?php echo htmlspecialchars($image['name']); ?></h5>
                        <p class="card-text">Taille: <?php echo round($image['size'] / 1024, 2); ?> Ko</p>
                        <a href="liste_images.php?id=<?php echo htmlspecialchars($image['id']); ?>" class="btn btn-danger">Supprimer</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <a href="add_image.php" class="btn btn-info mt-3">Ajouter une image</a>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
