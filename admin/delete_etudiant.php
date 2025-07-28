<?php
    // Inclusion du fichier de connexion à la base de données
    require_once '../db/Db_project.php';

    // Vérification si l'ID de l'étudiant à supprimer est passé en paramètre dans l'URL
    if(isset($_GET['id'])) {
        // Récupération de l'ID de l'étudiant depuis l'URL
        $id = $_GET['id'];

        // Requête SQL pour supprimer l'étudiant avec l'ID spécifié
        $sql = "DELETE FROM etudiants WHERE id = :id";

        // Préparation de la requête
        $stmt = $conn->prepare($sql);

        // Liaison des paramètres
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Exécution de la requête
        if($stmt->execute()) {
            // Redirection vers la page de liste des étudiants après la suppression
            header('Location: liste_etudiants.php');
            exit;
        } else {
            // En cas d'erreur lors de la suppression, affichage d'un message d'erreur
            echo "Erreur lors de la suppression de l'étudiant.";
        }
    } else {
        // Si l'ID de l'étudiant n'est pas passé en paramètre dans l'URL, affichage d'un message d'erreur
        echo "ID de l'étudiant non spécifié.";
    }
?>
