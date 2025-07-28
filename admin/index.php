<?php
session_start(); // Démarrer la session
require_once '../db/Db_project.php'; // Inclure le fichier de connexion à la base de données

// Fonction pour rediriger vers une autre page
function redirect($location) {
    header("Location: $location");
    exit;
}
// Fonction pour authentifier l'admin
function loginUser($email, $password, $conn) {
    try {
        // Préparer la requête pour récupérer l'admin par email
        $stmt = $conn->prepare("SELECT * FROM admin WHERE email = :email");
        $stmt->bindParam(':email', $email); // Lier le paramètre email
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC); // Récupérer les données de l'admin

        // Vérifier si l'admin existe
        if ($user) {
            // Vérifier si le mot de passe est correct
            if (password_verify($password, $user['password'])) {
                // Stocker les informations de l'admin dans la session
                $_SESSION['user'] = array(
                    'id' => $user['id'], 
                    'name' => $user['username'], 
                );
                redirect('home.php'); // Rediriger vers la page d'accueil
            } else {
                $_SESSION['failedMsg'] = "Mot de passe incorrect.";
                redirect('index.php'); // Rediriger vers la page de connexion
            }
        } else {
            $_SESSION['failedMsg'] = "L'utilisateur n'existe pas.";
            redirect('index.php'); // Rediriger vers la page de connexion
        }
    } catch (PDOException $e) {
        // En cas d'erreur, afficher un message et rediriger
        $_SESSION['failedMsg'] = "Erreur lors de la connexion. Veuillez réessayer.";
        redirect('index.php'); // Rediriger vers la page de connexion
    }
}

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        // Vérifier si les champs email et password sont remplis
        if (isset($_POST['email']) && isset($_POST['password'])) {
            loginUser($_POST['email'], $_POST['password'], $conn); // Appeler la fonction de connexion
        } else {
            $_SESSION['failedMsg'] = "Veuillez remplir tous les champs du formulaire.";
            redirect('index.php'); // Rediriger vers la page de connexion
        }
    }
}

// Récupérer les messages de succès ou d'échec
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
    <?php include '../all_component/allcss.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-XBRsFqYf8ImjoXq3/fbnwaoZ+1CCYqteKQlq5PSb1yESvIq7xRHQ11OQr1mOKPO56QKMJWcg9lLYkYimobKYgA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style type="text/css">
        .crd-ho:hover {
            background-color: #fcf7f7;
        }
    </style>
</head>
<body style="background-color: #f0f1f2;">
<?php include '../all_component/header_accueil.php'; // Inclure le header ?>
    <div class="container">
        <br><br><br>
        <div class="row mt-2">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-center"><i class="fas fa-right-to-bracket"></i> Connexion</h3>
                        <!-- Afficher un message d'erreur si présent -->
                        <?php if (!empty($failedMsg)) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $failedMsg ?>
                            </div>
                        <?php endif; ?>
                        <!-- Formulaire de connexion -->
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
                                <button type="submit" class="btn btn-primary mr-2" name="login"><i class="fas fa-sign-in-alt"></i>Se connecter</button>
                                <button type="button" class="btn btn-secondary" id="cancelBtn"><i class="fas fa-times"></i>Annuler</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <?php include '../all_component/footer.php'; // Inclure le footer ?>
    <script>
        // Réinitialiser les champs du formulaire et les messages lors du clic sur le bouton "Annuler"
        document.getElementById("cancelBtn").addEventListener("click", function() {
            document.getElementById("inputEmail").value = "";
            document.getElementById("inputPassword").value = "";
            <?php unset($_SESSION['failedMsg']); ?>
            <?php unset($_SESSION['succMsg']); ?>
        });
    </script>
</body>
</html>
