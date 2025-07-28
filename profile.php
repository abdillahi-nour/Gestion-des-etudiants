<?php
session_start();
require_once 'db/Db_project.php';

// on verifie  si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: home.php');
    exit;
}

// Récupération les informations de l'etudiant connecté
$userId = $_SESSION['user']['id'];
$stmt = $conn->prepare("SELECT * FROM etudiants WHERE id = :id");
$stmt->bindParam(':id', $userId);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
// Vérifier si l'etudiant existe
if (!$user) {
    $_SESSION['failedMsg'] = "Utilisateur non trouvé.";
    header('Location: home.php');
    exit;
}
// Si le formulaire de modification est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $newNom = $_POST['nom'];
    $newEmail = $_POST['email'];
    $newVille = $_POST['ville'];

    // Mettre à jour les informations de l'etudiant dans la base de données
    $updateStmt = $conn->prepare("UPDATE etudiants SET nom = :nom, login = :email, ville = :ville WHERE id = :id");
    $updateStmt->bindParam(':nom', $newNom);
    $updateStmt->bindParam(':email', $newEmail);
    $updateStmt->bindParam(':ville', $newVille);
    $updateStmt->bindParam(':id', $userId);
    $updateStmt->execute();

    // Rediriger vers la page de profil
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="wrapper">
    <!-- Main Content -->
    <div class="main-content">
        <h2 class="mb-4">Mon Profil</h2>
        <div class="card">
            <div class="card-body">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label for="nom">Nom:</label>
                        <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($user['nom']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['login']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="ville">Ville:</label>
                        <input type="text" class="form-control" id="ville" name="ville" value="<?= htmlspecialchars($user['ville']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="note1">Note 1:</label>
                        <p><?= htmlspecialchars($user['note1']); ?></p>
                    </div>
                    <div class="form-group">
                        <label for="note2">Note 2:</label>
                        <p><?= htmlspecialchars($user['note2']); ?></p>
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
