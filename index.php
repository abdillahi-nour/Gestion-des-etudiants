<?php
session_start();
require_once 'db/Db_project.php'; 

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

function loginUser($email, $password, $conn) {
    try {
        $stmt = $conn->prepare("SELECT * FROM etudiants WHERE login = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($password === $user['pass']) { // Vérification du mot de passe
                $_SESSION['user'] = array(
                    'id' => $user['id'], 
                    'name' => $user['nom'], 
                );
                $_SESSION['succMsg'] = "Connexion réussie.";
                redirect('home.php');
            } else {
                $_SESSION['failedMsg'] = "Mot de passe incorrect.";
                redirect('index.php');
            }
        } else {
            $_SESSION['failedMsg'] = "L'utilisateur n'existe pas.";
            redirect('index.php');
        }
    } catch (PDOException $e) {
        $_SESSION['failedMsg'] = "Erreur lors de la connexion. Veuillez réessayer.";
        redirect('index.php');
    }
}

function registerUser($nom, $email, $pass, $ville, $conn) {
   // Vérification si le login est unique
   $stmt = $conn->prepare("SELECT id FROM etudiants WHERE login = :login");
   $stmt->bindParam(':login', $email);
   $stmt->execute();
   $result = $stmt->fetch(PDO::FETCH_ASSOC);

   if ($result) {
       $_SESSION['failedMsg'] = "L'email est déjà utilisé. Veuillez en choisir un autre.";
       redirect('index.php');
   } else {
       // Obtenir les coordonnées de la ville
       $coordinates = getCoordinates($ville);
       $latitude = $coordinates['lat'];
       $longitude = $coordinates['lng'];
       
       // Vérification si les coordonnées ont été trouvées
       if ($latitude === null || $longitude === null) {
           $_SESSION['failedMsg'] = "Impossible de trouver les coordonnées pour la ville spécifiée. Veuillez vérifier le nom de la ville et réessayer.";
           redirect('index.php');
       }

       // Insértion les données dans la table
       $stmt = $conn->prepare("INSERT INTO etudiants (login, pass, nom, ville, latitude, longitude) VALUES (:email, :pass, :nom, :ville, :latitude, :longitude)");
       $stmt->bindParam(':email', $email);
       $stmt->bindParam(':pass', $pass);
       $stmt->bindParam(':nom', $nom);
       $stmt->bindParam(':ville', $ville);
       $stmt->bindParam(':latitude', $latitude);
       $stmt->bindParam(':longitude', $longitude);

       if ($stmt->execute()) {
           $_SESSION['succMsg'] = "Inscription réussie. Vous pouvez maintenant vous connecter.";
           redirect('index.php');
       } else {
           $_SESSION['failedMsg'] = "Erreur lors de l'inscription. Veuillez réessayer.";
           redirect('index.php');
       }
   }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            loginUser($_POST['email'], $_POST['password'], $conn);
        } else {
            $_SESSION['failedMsg'] = "Veuillez remplir tous les champs du formulaire.";
            redirect('index.php');
        }
    } elseif (isset($_POST['register'])) {
        if (isset($_POST['nom']) && isset($_POST['email']) && isset($_POST['pass']) && isset($_POST['adress'])) {
            registerUser($_POST['nom'], $_POST['email'], $_POST['pass'], $_POST['adress'], $conn);
        } else {
            $_SESSION['failedMsg'] = "Veuillez remplir tous les champs du formulaire.";
            redirect('index.php');
        }
    }
}

$succMsg = isset($_SESSION['succMsg']) ? $_SESSION['succMsg'] : '';
$failedMsg = isset($_SESSION['failedMsg']) ? $_SESSION['failedMsg'] : '';

// Réinitialisation des messages après leur affichage
unset($_SESSION['succMsg']);
unset($_SESSION['failedMsg']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <?php include 'all_component/allcss.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-XBRsFqYf8ImjoXq3/fbnwaoZ+1CCYqteKQlq5PSb1yESvIq7xRHQ11OQr1mOKPO56QKMJWcg9lLYkYimobKYgA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style type="text/css">
        .crd-ho:hover {
            background-color: #fcf7f7;
        }
    </style>
</head>
<body style="background-color: #f0f1f2;">
<?php include 'all_component/header_accueil.php'; ?>
    <div class="container">
        <br><br><br>
        <div class="row mt-2">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-center"><i class="fas fa-right-to-bracket"></i> S'inscrire / Se connecter</h3>
                        <?php if (!empty($succMsg)) : ?>
                            <div class="alert alert-success" role="alert">
                                <?= $succMsg ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($failedMsg)) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $failedMsg ?>
                            </div>
                        <?php endif; ?>
                        <form id="loginForm" method="post">
                            <div class="form-group">
                                <label for="inputEmail"><i class="fas fa-envelope"></i> Email:</label>
                                <input type="email" class="form-control" id="inputEmail" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword"><i class="fas fa-lock"></i> Password:</label>
                                <input type="password" class="form-control" id="inputPassword" name="password" required>
                            </div>
                            <div class="text-center mt-2">
                                <button type="submit" class="btn btn-primary mr-2" name="login"><i class="fas fa-sign-in-alt"></i> Se connecter</button>
                                <button type="button" class="btn btn-secondary" id="registerBtn"><i class="fas fa-user-plus"></i> S'inscrire</button>
                            </div>
                        </form>
                        <div id="registerForm" style="display: none;">
                            <!-- Formulaire d'inscription -->
                            <form method="post">
                                <div class="form-group">
                                    <label for="nom"><i class="fas fa-user"></i> Nom Complet:</label>
                                    <input type="text" class="form-control" id="nom" name="nom" required>
                                </div>
                                <div class="form-group">
                                    <label for="email"><i class="fas fa-envelope"></i> Adresse mail:</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="adress"><i class="fas fa-map-marker-alt"></i> Ville:</label>
                                    <input type="text" class="form-control" id="adress" name="adress" required>
                                </div>
                                <div class="form-group">
                                    <label for="pass"><i class="fas fa-lock"></i> Mot de passe:</label>
                                    <input type="password" class="form-control" id="pass" name="pass" required>
                                </div>
                                <div class="text-center mt-2">
                                    <button type="submit" class="btn btn-primary mr-2" name="register"><i class="fas fa-user-plus"></i> S'inscrire</button>
                                    <button type="button" class="btn btn-secondary" id="cancelRegBtn"><i class="fas fa-times"></i> Annuler</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <?php include 'all_component/footer.php'; ?>
    <script>
        document.getElementById("registerBtn").addEventListener("click", function() {
            document.getElementById("loginForm").style.display = "none";
            document.getElementById("registerForm").style.display = "block";
        });

        document.getElementById("cancelRegBtn").addEventListener("click", function() {
            document.getElementById("loginForm").style.display = "block";
            document.getElementById("registerForm").style.display = "none";
        });
    </script>
</body>
</html>
