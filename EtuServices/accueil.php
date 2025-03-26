<?php
// Vérifiez si le formulaire a été soumis
if (isset($_POST['stats'])) {
    // La commande shell à exécuter
    $cmd = "C:\Users\pulci\AppData\Local\Programs\Python\Python311\python.exe redis_manager.py afficher_statistiques";
    $command = escapeshellcmd($cmd);
    $output = shell_exec($command);

    // Exécution de la commande shell
    $output = shell_exec($command);

    // Affichage du résultat de la commande
    echo "<pre>$output</pre>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
</head>
<body>
    <h1>Bienvenue sur notre site !</h1>
    <p>Ceci est la page d'accueil. Vous pouvez naviguer vers d'autres pages en utilisant les liens ci-dessous :</p>
    <ul>
        <li><a href="login.php">Se connecter</a></li>
        <li><a href="register.php">S'inscrire</a></li>
        <li><a href="services.php">Nos services</a></li>
    </ul>
    <form method="POST">
        <!-- Bouton qui déclenche la commande -->
        <button type="submit" name="stats">Mettre à jour les statistiques</button>
    </form>
</body>
</html>
