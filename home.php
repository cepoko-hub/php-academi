<?php
session_start();

// Vérification de la session utilisateur
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Connexion à la base de données
$host = 'localhost';
$dbname = 'academie_magique';
$user = 'root'; 
$password = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupération des informations utilisateur
$user_id = $_SESSION['user_id'];
try {
    $stmt = $pdo->prepare("SELECT noms_user, noms_element AS primary_element, 
                           (SELECT noms_element FROM user_element ue 
                            JOIN element e ON ue.element_id = e.element_id 
                            WHERE ue.user_id = u.user_id LIMIT 1) AS secondary_element 
                           FROM user u 
                           JOIN element e ON u.element_id = e.element_id 
                           WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "Utilisateur non trouvé.";
        exit;
    }
} catch (PDOException $e) {
    die("Erreur lors de la récupération des informations utilisateur : " . $e->getMessage());
}

$nom_user = htmlspecialchars($user['noms_user']);
$primary_element = htmlspecialchars($user['primary_element']);
$secondary_element = htmlspecialchars($user['secondary_element'] ?? '');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Académie de Fordhiver</title>
    <link rel="stylesheet" href="asset/css/style_index.css">
    <style>
        /* Style spécifique pour la page d'accueil */
        nav {
            background-color: #1a213d;
            padding: 10px;
            display: flex;
            justify-content: space-around;
        }

        nav a {
            color: #e0e5ef;
            text-decoration: none;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }

        nav a:hover {
            color: #98c1d9;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
            color: #e0e5ef;
        }
    </style>
</head>
<body>
    <nav>
        <a href="créature.php">Créature</a>
        <a href="sort.php">Sort</a>
        <a href="asset/php-subpage/logout.php">Déconnexion</a>
    </nav>

    <main>
        <h2>Bienvenue <?= $nom_user ?> sur le site de l'académie de Fordhiver, vous êtes actuellement <?= $primary_element ?><?= $secondary_element ? ' et ' . $secondary_element : '' ?>.</h2>
    </main>
</body>
</html>
