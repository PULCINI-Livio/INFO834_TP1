<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hashage du mot de passe pour le stocker de manière sécurisée
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connexion à la base de données
    require 'config.php'; // Connexion à la base de données

    // Insertion de l'utilisateur dans la table utilisateurs
    $sql = "INSERT INTO utilisateurs (nom, prenom, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erreur de préparation de la requête : " . $conn->error);
    }

    $stmt->bind_param("ssss", $nom, $prenom, $email, $hashed_password);
    if ($stmt->execute()) {
        echo "<p>Utilisateur inscrit avec succès !</p>";
    } else {
        echo "<p>Erreur lors de l'inscription.</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <h1>Inscription</h1>
    <form action="register.php" method="post">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required>
        <br>
        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required>
        <br>
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">S'inscrire</button>
    </form>
    <p><a href="accueil.php">Retour à l'accueil</a></p>
</body>
</html>
