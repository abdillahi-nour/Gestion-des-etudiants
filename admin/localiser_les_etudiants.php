<?php
// Fonction pour récupérer les coordonnées des étudiants depuis la base de données
function getStudentLocations() {
    require_once '../db/Db_project.php'; // le chemin d'accès à Db_project.php 

    try {
        // la requête pour récupérer les données des étudiants
        $stmt = $conn->prepare('SELECT nom, latitude, longitude FROM etudiants');
        
        // Exécution de la requête
        $stmt->execute();
        
        // Récupération des résultats
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Retourner les résultats au format JSON
        return json_encode($results);
    } catch(PDOException $e) {
        // En cas d'erreur, afficher l'erreur
        die("Erreur lors de la récupération des données des étudiants : " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>  
<head>
    <title>Géolocalisation des étudiants</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAqcwOajVxQCL8L2nF6cDr2peme5XdXSoU"></script>
    <script>
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 2,
            center: {lat: 48.8566, lng: 2.3522} // Coordonnées initiales
        });

        // Récupérer les données des étudiants depuis la base de données
        var studentLocations = <?php echo getStudentLocations(); ?>;
        
        // Créer un infowindow
        var infowindow = new google.maps.InfoWindow();
        
        // Placer un marqueur pour chaque étudiant
        studentLocations.forEach(function(student) {
            var marker = new google.maps.Marker({
                position: {lat: parseFloat(student.latitude), lng: parseFloat(student.longitude)},
                map: map,
                title: student.nom // Nom complet de l'étudiant 
            });

           
        });
    }
</script>

</head>
<body onload="initMap()">
    <h1>Géolocalisation des étudiants</h1>
    <div id="map" style="height: 500px; width: 100%;"></div>
</body>

</html>
