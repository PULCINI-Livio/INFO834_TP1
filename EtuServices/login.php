<?php
session_start();
require 'config.php'; // Connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérification de la connexion à la base de données
    if (!$conn) {
        die("Erreur de connexion à la base de données : " . mysqli_connect_error());
    }

    // Récupérer l'utilisateur depuis la base de données
    $sql = "SELECT id, nom, prenom, password FROM utilisateurs WHERE email = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Erreur de préparation de la requête : " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $nom, $prenom, $hashed_password);
    $stmt->fetch();

    // Vérifier le mot de passe
    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        $_SESSION['user'] = ['id' => $id, 'nom' => $nom, 'prenom' => $prenom, 'email' => $email];
        header("Location: services.php");
        exit();
    } else {
        $error = "Email ou mot de passe incorrect.";
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
    <title>Connexion</title>
</head>
<body>
    <h1>Connexion</h1>
    <form action="login.php" method="post">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Se connecter</button>
    </form>
    <?php if (isset($error)): ?>
        <p><?= $error ?></p>
    <?php endif; ?>
    <p><a href="accueil.php">Retour à l'accueil</a></p>
</body>
</html>
