
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

// Vérification si l'utilisateur est administrateur
$user_id = $_SESSION['user_id'];
try {
    $stmt = $pdo->prepare("SELECT noms_element FROM user u JOIN element e ON u.element_id = e.element_id WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user_element = $stmt->fetchColumn();
    $is_admin = ($user_element === 'administrateur');
} catch (PDOException $e) {
    die("Erreur lors de la vérification de l'élément utilisateur : " . $e->getMessage());
}

// Récupération des créatures avec LEFT JOIN
try {
    $stmt = $pdo->query("SELECT c.nom_creature, 
                                IFNULL(c.img_creature, 'asset/img/default.jpg') AS img_creature, 
                                t.noms_type, 
                                c.description_creature, 
                                u.noms_user
                         FROM creature c
                         LEFT JOIN type t ON c.type_id = t.type_id
                         LEFT JOIN user u ON c.user_id = u.user_id");
    $creatures = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des créatures : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créatures - Académie de Fordhiver</title>
    <link rel="stylesheet" href="asset/css/style_créature.css">
</head>
<body>
    <nav>
        <a href="home.php">Home</a>
        <?php if ($is_admin): ?>
            <a href="asset/php-subpage/édit_créature.php">Ajouter</a>
        <?php endif; ?>
        <a href="asset/php-subpage/logout.php">Déconnexion</a>
    </nav>

    <main>
        <section class="creature-container">
            <?php if (empty($creatures)): ?>
                <p>Aucune créature trouvée dans la base de données.</p>
            <?php else: ?>
                <?php foreach ($creatures as $creature): ?>
                    <div class="creature-box type-<?= htmlspecialchars(strtolower($creature['noms_type'] ?? 'inconnu')) ?>">
                        <h3><?= htmlspecialchars($creature['nom_creature'] ?? 'Créature sans nom') ?></h3>
                        <img src="<?= "/exo php académi de mage/asset/php-subpage/". htmlspecialchars($creature['img_creature'] ?? 'asset/img/default.jpg') ?>" alt="Image de <?= htmlspecialchars($creature['nom_creature'] ?? 'Inconnue') ?>">
                        <p><strong>Type :</strong> <?= htmlspecialchars($creature['noms_type'] ?? 'Inconnu') ?></p>
                        <p><?= htmlspecialchars($creature['description_creature'] ?? 'Aucune description disponible.') ?></p>
                        <small>Ajouté par : <?= htmlspecialchars($creature['noms_user'] ?? 'Utilisateur inconnu') ?></small>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
