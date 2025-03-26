<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$email = $_SESSION['user']['email'];

// Vérifiez si le formulaire a été soumis
if (isset($_POST['achat'])) {
    $service = "achat";
    // La commande shell à exécuter
    $cmd = "C:\Users\pulci\AppData\Local\Programs\Python\Python311\python.exe redis_manager.py incremente_achat_vente $email $service";
    $command = escapeshellcmd($cmd);
    $output = shell_exec($command);

    // Exécution de la commande shell
    $output = shell_exec($command);

    // Affichage du résultat de la commande
    echo "<pre>$output</pre>";
}

if (isset($_POST['vente'])) {
    $service = "vente";
    // La commande shell à exécuter
    $cmd = "C:\Users\pulci\AppData\Local\Programs\Python\Python311\python.exe redis_manager.py incremente_achat_vente $email $service";
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
    <title>Nos services</title>
</head>
<body>
    <h1>Nos services</h1>
    <p>Bienvenue, <?= htmlspecialchars($user['prenom']) ?> <?= htmlspecialchars($user['nom']) ?> !</p>
    <p>Voici une liste de nos services :</p>
    <ul>
        <li>
            <form method="POST">
                <!-- Bouton qui déclenche la commande -->
                <button type="submit" name="achat">Acheter</button>
            </form>
        </li>
        <li>
            <form method="POST">
                <!-- Bouton qui déclenche la commande -->
                <button type="submit" name="vente">Vendre</button>
            </form>
        </li>
        <li>Service 3 : Description du service 3.</li>
    </ul>
    <p><a href="accueil.php">Retour à l'accueil</a></p>
    <p><a href="logout.php">Se déconnecter</a></p>
</body>
</html>
