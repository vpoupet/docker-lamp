<?php
$servername = "mariadb";  // nom du serveur MariaDB (ici le nom du container Docker)
$username = "root";       // nom d'utilisateur
$password = "admin";      // mot de passe (le mot de passe de root est défini dans le fichier docker-compose.yml)

// Ouvrir une connexion
$conn = new mysqli($servername, $username, $password);

// Tester la connexion
if ($conn->connect_error) {
  die("Erreur de connexion à la base de données: " . $conn->connect_error);
}
echo "Connexion réussie à la base de données.";
?>
