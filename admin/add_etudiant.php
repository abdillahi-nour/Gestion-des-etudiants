<?php
session_start();
require_once '../db/Db_project.php'; //  fichier de connexion à la base de données

function redirect($location) {
    header("Location: $location");
    exit;
}

function getCoordinates($city) {
    $apiKey = 'AIzaSyAqcwOajVxQCL8L2nF6cDr2peme5XdXSoU';  // Clé API Google Maps
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    // Collectez les données du formulaire
    $nom = $_POST['nom'];
    $login = $_POST['email'];
    $pass = $_POST['pass'];
    $ville = $_POST['adress'];

    // Vérifiez si le login est unique
    $stmt = $conn->prepare("SELECT id FROM etudiants WHERE login = :login");
    $stmt->bindParam(':login', $login);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $_SESSION['failedMsg'] = "l email est déjà utilisé. Veuillez en choisir un autre.";
        redirect('add_etudiant.php');
    } else {
        // Obtenez les coordonnées de la ville
        $coordinates = getCoordinates($ville);
        $latitude = $coordinates['lat'];
        $longitude = $coordinates['lng'];
         // Vérifiez si les coordonnées ont été trouvées
         if ($latitude === null || $longitude === null) {
            $_SESSION['failedMsg'] = "Impossible de trouver les coordonnées pour la ville spécifiée.";
            redirect('add_etudiant.php');
        }

        // Insérez les données dans la table
        $stmt = $conn->prepare("INSERT INTO etudiants (login, pass, nom, ville, latitude, longitude) VALUES (:login, :pass, :nom, :ville, :latitude, :longitude)");
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':pass', $pass);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':ville', $ville);
        $stmt->bindParam(':latitude', $latitude);
        $stmt->bindParam(':longitude', $longitude);

        if ($stmt->execute()) {
            $_SESSION['succMsg'] = "Inscription réussie. Vous pouvez maintenant vous connecter.";
            redirect('liste_etudiants.php');
        } else {
            $_SESSION['failedMsg'] = "Erreur lors de l'inscription. Veuillez réessayer.";
            redirect('add_etudiant.php');
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>add student</title>
    <?php include 'allcss.php'; ?>
    
  
</head>
<body style="background-color: #f0f1f2;">
    <?php include 'navbar.php'; ?>
    <div class="container">
        <div class="row mt-2">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-center">Ajout d'un étudiant</h3>
                        
                        <?php
                        if (isset($_SESSION['failedMsg'])) {
                            echo "<div class='alert alert-danger'>" . $_SESSION['failedMsg'] . "</div>";
                            unset($_SESSION['failedMsg']);
                        }
                        if (isset($_SESSION['succMsg'])) {
                            echo "<div class='alert alert-success'>" . $_SESSION['succMsg'] . "</div>";
                            unset($_SESSION['succMsg']);
                        }
                        ?>
                        <!-- Formulaire d'inscription -->
                        <form id="registerForm" method="post">
                            <div class="form-group">
                                <label for="nom"><i class="fas fa-user"></i> Nom Complet:</label>
                                <input type="text" class="form-control" id="nom" name="nom" required>
                            </div>
                            <div class="form-group">
                                <label for="email"><i class="fas fa-envelope"></i> Address mail:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="adress"><i class="fas fa-map-marker-alt"></i> Ville :</label>
                                <input type="text" class="form-control" id="adress" name="adress" required>
                            </div>
                            <div class="form-group">
                                <label for="pass"><i class="fas fa-lock"></i> Password</label>
                                <input type="password" class="form-control" id="pass" name="pass" required>
                            </div>
                            <div class="text-center mt-2">
                                <button type="submit" class="btn btn-primary" name="register"><i class="fas fa-user-plus"></i> Enregistrer</button>
                                <button type="button" id="cancelBtn" class="btn btn-secondary ml-2"><i class="fas fa-times"></i> Annuler</button>
                            </div>
                        </form>
                    </div>
                    <br><br>
                </div>
            </div>
        </div>
    </div>
    <?php include '../all_component/footer.php'; ?>
    <script>
        document.getElementById("cancelBtn").addEventListener("click", function() {
            window.location.href = "home.php";
        });
    </script>

</body>
</html>
