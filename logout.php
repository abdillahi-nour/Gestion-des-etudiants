<?php
session_start(); // Démarrer la session pour accéder aux variables de session

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user'])) {
    // Si l'utilisateur est connecté, détruire la session et rediriger vers la page de connexion
    session_destroy(); // Détruire toutes les données associées à la session
    header('Location: index.php'); // Rediriger vers la page de connexion
    exit; // Arrêter l'exécution du script
} else {
    // Si l'utilisateur n'est pas connecté, simplement rediriger vers la page de connexion
    header('Location: index.php'); // Rediriger vers la page de connexion
    exit; // Arrêter l'exécution du script
}
?>
