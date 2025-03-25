<?php
$host = "localhost"; // Serveur MySQL
$user = "root"; // Nom d'utilisateur (par défaut "root")
$password = ""; // Mot de passe (par défaut vide)
$dbname = "polytech"; // Nom de la base de données

// Connexion à MySQL
$conn = new mysqli($host, $user, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}
?>
