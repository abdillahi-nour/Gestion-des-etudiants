<?php
session_start();
require_once '../db/Db_project.php'; //  fichier de connexion à la base de données

function redirect($location) {
    header("Location: $location");
    exit;
}

function getCoordinates($city) {
    $apiKey = 'AIzaSyAglLp24plRpeijcaCXzU5oxvNgR0uRflo';  // Clé API Google Maps
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($city) . "&key=" . $apiKey;
    
    $response = file_get_contents($url);
    $response = json_decode($response, true);

    if ($response['status'] == 'OK') {
        $latitude = $response['results'][0]['geometry']['location']['lat'];
        $longitude = $response['results'][0]['geometry']['location']['lng'];
        return ['lat' => $latitude, 'lng' => $longitude];
    } else {
        return ['lat' => null, 'lng' => null];
    }
}

// Vérifiez si l'identifiant de l'étudiant est passé dans l'URL
if (!isset($_GET['id'])) {
    // Rediriger si l'identifiant de l'étudiant n'est pas fourni
    header('Location: liste_etudiants.php');
    exit();
}

// Récupérer l'identifiant de l'étudiant depuis l'URL
$student_id = $_GET['id'];

// Récupérer les données de l'étudiant à partir de la base de données
$stmt = $conn->prepare("SELECT * FROM etudiants WHERE id = :id");
$stmt->bindParam(':id', $student_id);
$stmt->execute();
$student = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'étudiant existe
if (!$student) {
    // Rediriger si l'étudiant n'est pas trouvé
    header('Location: liste_etudiants.php');
    exit();
}

// Vérifiez si le formulaire est soumis pour la mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    // Collectez les données du formulaire
    $nom = $_POST['nom'];
    $login = $_POST['email'];
    $pass = $_POST['pass'];
    $note1 = $_POST['module'];
    $note2 = $_POST['module2'];
    $ville = $_POST['adress'];

    // Obtenez les coordonnées de la ville
    $coordinates = getCoordinates($ville);
    $latitude = $coordinates['lat'];
    $longitude = $coordinates['lng'];

    // Mettre à jour les données dans la base de données
    $stmt = $conn->prepare("UPDATE etudiants SET login = :login, pass = :pass, nom = :nom, note1 = :note1, note2 = :note2,moyenne = :moyenne,
     ville = :ville, latitude = :latitude, longitude = :longitude WHERE id = :id");
    $moyenne = ($note1 + $note2) / 2;
    $stmt->bindParam(':id', $student_id);
    $stmt->bindParam(':login', $login);
    $stmt->bindParam(':pass', $pass);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':note1', $note1);
    $stmt->bindParam(':note2', $note2);
    $stmt->bindParam(':moyenne', $moyenne);
    $stmt->bindParam(':ville', $ville);
    $stmt->bindParam(':latitude', $latitude);
    $stmt->bindParam(':longitude', $longitude);

    if ($stmt->execute()) {
        $_SESSION['succMsg'] = "Les informations de l'étudiant ont été mises à jour avec succès.";
        redirect('liste_etudiants.php');
    } else {
        $_SESSION['failedMsg'] = "Erreur lors de la mise à jour des informations de l'étudiant. Veuillez réessayer.";
        redirect('update_etudiant.php?id=' . $student_id);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modifier un étudiant</title>
    <?php include 'allcss.php'; ?>
</head>
<body style="background-color: #f0f1f2;">
    <?php include 'navbar.php'; ?>
    <div class="container">
        <div class="row mt-2">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-center">  <i class="fas fa-edit"></i> Modifier les infos de l etudiant</h3>
                        
                        <?php
                        if (isset($_SESSION['failedMsg'])) {
                            echo "<div class='alert alert-danger'>" . $_SESSION['failedMsg'] . "</div>";
                            unset($_SESSION['failedMsg']);
                        }
                        ?>
                        
                        <!-- Formulaire de mise à jour -->
                        <form method="post">
                            <div class="form-group">
                                <label for="nom"><i class="fas fa-user"></i> Nom Complet:</label>
                                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $student['nom']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email"><i class="fas fa-envelope"></i> Adresse mail:</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $student['login']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="adress"><i class="fas fa-map-marker-alt"></i> Ville :</label>
                                <input type="text" class="form-control" id="adress" name="adress" value="<?php echo $student['ville']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label
                                for="module"><i class="fas fa-book"></i> Note de module 1 :</label>
                                <input type="number" class="form-control" id="module" name="module" value="<?php echo $student['note1']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="module2"><i class="fas fa-book"></i> Note de module 2 :</label>
                                <input type="number" class="form-control" id="module2" name="module2" value="<?php echo $student['note2']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="pass"><i class="fas fa-lock"></i> Mot de passe:</label>
                                <input type="password" class="form-control" id="pass" name="pass" value="<?php echo $student['pass']; ?>" required>
                            </div>
                            <div class="text-center mt-2">
                                <button type="submit" class="btn btn-primary" name="update"><i class="fas fa-user-edit"></i> Mettre à jour</button>
                                <a href="liste_etudiants.php" class="btn btn-secondary ml-2"><i class="fas fa-times"></i> Annuler</a>
                            </div>
                        </form>
                    </div>
                    <br><br>
                </div>
            </div>
        </div>
    </div>
    <?php include '../all_component/footer.php'; ?>
</body>
</html>
