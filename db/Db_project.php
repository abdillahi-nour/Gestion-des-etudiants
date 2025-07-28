<?php
$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "master_rsi";     

try {
    // Crée une nouvelle connexion PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    
    // Configure PDO pour qu'il lance des exceptions en cas d'erreurs
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Message pour indiquer que la connexion est réussie 
    // echo "Connexion réussie";
} catch (PDOException $e) {
    // Capture l'exception et affiche un message d'erreur
    die("Échec de la connexion : " . $e->getMessage());
}
?>
