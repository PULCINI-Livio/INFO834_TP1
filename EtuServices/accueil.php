<?php
// Vérifiez si le formulaire a été soumis
$stats = "";
if (isset($_POST['stats'])) {
    // La commande shell à exécuter
    $cmd = "C:\Users\pulci\AppData\Local\Programs\Python\Python311\python.exe redis_manager.py afficher_statistiques";
    $command = escapeshellcmd($cmd);
    $output = shell_exec($command);
    var_dump($output);
    // Nettoyage et stockage des données
    $stats = nl2br(htmlspecialchars($output));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            color: #333;
        }
        .stats-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
    </style>
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

    <?php if (!empty($stats)): ?>
    <div class="stats-container">
        <h2>Statistiques</h2>
        <?php
        // Traitement des données et affichage structuré
        $lines = explode("<br />", $stats);
        echo "<table>";
        
        foreach ($lines as $line) {
            if (!empty(trim($line))) {
                echo "<tr><td colspan='2'><strong>" . $line . "</strong></td></tr>";
            }
        }
        
        echo "</table>";
        ?>
    </div>
    <?php endif; ?>
</body>
</html>
