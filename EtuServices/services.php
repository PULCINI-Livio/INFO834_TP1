<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos services</title>
</head>
<body>
    <h1>Nos services</h1>
    <p>Bienvenue, <?= htmlspecialchars($user['prenom']) ?> <?= htmlspecialchars($user['nom']) ?> !</p>
    <p>Voici une liste de nos services :</p>
    <ul>
        <li>Service 1 : Description du service 1.</li>
        <li>Service 2 : Description du service 2.</li>
        <li>Service 3 : Description du service 3.</li>
    </ul>
    <p><a href="accueil.php">Retour à l'accueil</a></p>
    <p><a href="logout.php">Se déconnecter</a></p>
</body>
</html>
