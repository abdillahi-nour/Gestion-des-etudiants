<?php
session_start();
require_once 'db/Db_project.php';



$userId = $_SESSION['user']['id'];
$stmt = $conn->prepare("SELECT * FROM etudiants WHERE id = :id");
$stmt->bindParam(':id', $userId);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $_SESSION['failedMsg'] = "Utilisateur non trouvé.";
    header('Location: index.php');
    exit;
}

// Déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<!-- Header -->
<div class="container-fluid" style="height: 10px; background-color: #303f9b;"></div>
<div class="container-fluid p-3 bg-light">
    <div class="row">
        <div class="col-md-3 text-success">
            <h3><i class="fas fa-tachometer-alt"></i> E-Project</h3>
        </div>
        <div class="col-md-6">
            <form class="form-inline my-2 my-lg-0" action="" method="GET">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="query">
                <button class="btn btn-primary my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
        <div class="col-md-3">
            <?php if (!empty($_SESSION['user'])): ?>
                <a class="btn btn-success text-white"><i class="fas fa-user"></i><?php echo htmlspecialchars($_SESSION['user']['name']); ?></a>
                <a data-toggle="modal" data-target="#exampleModalCenter" class="btn btn-primary text-white"> <i class="fas fa-sign-out-alt"></i> Logout</a>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- Deconnexion Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <h4>Do You want to logout?</h4>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a href="logout.php" type="button" class="btn btn-primary text-white">Logout</a>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<!-- End Header -->

<div class="wrapper">
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-sticky">
            <h5 class="sidebar-heading p-3">Dashboard</h5>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="loadContent('profile.php')"><i class="fas fa-user"></i> Mon profil</a>
                </li>
                <li class="nav-item">
                 <a class="nav-link" href="mon_cv.html"><i class="fas fa-address-card"></i> À propos de moi</a>
               </li>
                <li class="nav-item">
                    <a class="nav-link" href="matrice.html" onclick="loadContent('matrice.html')"><i class="fas fa-calculator"></i> Manipulation de matrices</a>
                </li>
              
                <li class="nav-item">
                   <a class="nav-link" href="quiz1.php"><i class="fas fa-question-circle fa-2x text-infos"></i> Quiz Javascript</a>
                </li>
                <li class="nav-item">
                   <a class="nav-link" href="quiz2.php"><i class="fas fa-question-circle fa-2x text-infos"></i> Quiz PHP</a>
                </li>
                <li class="nav-item">
                   <a class="nav-link" href="chatboot.php"><i class="fas fa-messages fa-2x text-infos"></i> Chatboot</a>
                </li>
                
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div id="mainContent" class="main-content">
        <h2 class="mb-4">Mon profil</h2>
        <p>Bienvenue ,voici mes informations :</p>
        <div class="card">
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item"><strong>Email:</strong> <?= htmlspecialchars($user['login']); ?></li>
                    <li class="list-group-item"><strong>Ville:</strong> <?= htmlspecialchars($user['ville']); ?></li>
                    <li class="list-group-item"><strong>Note 1:</strong> <?= htmlspecialchars($user['note1']); ?></li>
                    <li class="list-group-item"><strong>Note 2:</strong> <?= htmlspecialchars($user['note2']); ?></li>
                    <li class="list-group-item"><strong>Moy
                    enne:</strong> <?= htmlspecialchars($user['moyenne']); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer mt-auto">
    <p>&copy; 2024 Abdillahi Nour hassan. Tous droits réservés.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function loadContent(page) {
        fetch(page)
            .then(response => response.text())
            .then(data => {
                document.getElementById('mainContent').innerHTML = data;
            })
            .catch(error => console.error('Erreur lors du chargement du contenu :', error));
    }
</script>

</body>
</html>
