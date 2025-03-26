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
        // Appel au programme Python pour vérifier les connexions
        $cmd = "C:\Users\pulci\AppData\Local\Programs\Python\Python311\python.exe redis_manager.py check_and_increment_connections $email";
        $command = escapeshellcmd($cmd);
        $output = shell_exec($command);
        
        // Afficher la sortie du script pour debug
        var_dump($output);
        var_dump(trim($output) == "OK");
        // Si la connexion est autorisée, l'utilisateur peut accéder aux services
        if (trim($output) == "OK") {
            $_SESSION['user'] = ['id' => $id, 'nom' => $nom, 'prenom' => $prenom, 'email' => $email];
            header("Location: services.php");
            exit();
        } else {
            $error = "Trop de connexions. Accès refusé.";
        }
    } else {
        $error = "Email ou mot de passe incorrect.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!-- HTML form -->
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
