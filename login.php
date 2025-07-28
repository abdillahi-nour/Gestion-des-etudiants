<?php
session_start();
require_once 'db/Db_project.php'; 

function redirect($location) {
    header("Location: $location");
    exit;
}

function loginUser($email, $password, $conn) {
    try {
        $stmt = $conn->prepare("SELECT * FROM etudiant WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($password === $user['passw']) { // Vérification sans cryptage
                $_SESSION['user'] = array(
                    'id' => $user['id'], 
                    'name' => $user['username'], 
                );
                redirect('home.php');
            } else {
                $_SESSION['failedMsg'] = "Mot de passe incorrect.";
                redirect('login.php');
            }
        } else {
            $_SESSION['failedMsg'] = "L'utilisateur n'existe pas.";
            redirect('matrice.html');
        }
    } catch (PDOException $e) {
        $_SESSION['failedMsg'] = "Erreur lors de la connexion. Veuillez réessayer.";
        redirect('login.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            loginUser($_POST['email'], $_POST['password'], $conn);
        } else {
            $_SESSION['failedMsg'] = "Veuillez remplir tous les champs du formulaire.";
            redirect('login.php');
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
                        <h3 class="text-center"><i class="fas fa-right-to-bracket"></i> Connexion</h3>
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
                                <button type="submit" class="btn btn-primary mr-2" name="login"><i class="fas fa-sign-in-alt"></i>Se connecter</button>
                                <button type="button" class="btn btn-secondary" id="register"><i class="fas fa-times"></i>register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <?php include 'all_component/footer.php'; ?>
    <script>
        document.getElementById("register").addEventListener("click", function() {
           
        });
    </script>
</body>
</html>
