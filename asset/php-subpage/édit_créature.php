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
    if ($user_element !== 'administrateur') {
        header("Location: home.php");
        exit;
    }
} catch (PDOException $e) {
    die("Erreur lors de la vérification de l'élément utilisateur : " . $e->getMessage());
}

// Récupération des utilisateurs et créatures existantes
try {
    $stmt = $pdo->query("SELECT * FROM creature");
    $creatures = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt_users = $pdo->query("SELECT user_id, noms_user FROM user");
    $users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des données : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier / Ajouter des Créatures</title>
    <link rel="stylesheet" href="../css/style_créature.css">
    <style>
        form {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #1a213d;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        form input, form select, form textarea {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #3a467b;
            border-radius: 5px;
            background-color: #141a3b;
            color: #e0e5ef;
        }

        form button {
            padding: 10px;
            background-color: #1f6f8b;
            border: none;
            border-radius: 5px;
            color: #e0e5ef;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #3a9cb0;
        }

        form #delete-boutton{
            background-color:rgb(139, 31, 31);
        }

        form #delete-boutton:hover{
            background-color:rgb(176, 58, 58);
        }

    </style>
</head>
<body>
    <nav>
        <a href="../../créature.php">Retour</a>
        <a href="logout.php">Déconnexion</a>
    </nav>

    <main>
        <h1>Ajouter une nouvelle créature</h1>
        <form method="POST" action="ajout_creature.php" enctype="multipart/form-data">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required>

            <label for="image">Image :</label>
            <input type="file" id="image" name="image" accept="image/*">

            <label for="type">Type :</label>
            <select id="type" name="type" required>
                <option value="1">Aquatique</option>
                <option value="2">Démonique</option>
                <option value="3">Mi-bête</option>
                <option value="4">Mort vivant</option>
            </select>

            <label for="description">Description :</label>
            <textarea id="description" name="description" rows="4" required></textarea>

            <label for="user">Créateur :</label>
            <select id="user" name="user_id" required>
                <?php foreach ($users as $user): ?>
                    <option value="<?= htmlspecialchars($user['user_id']) ?>"><?= htmlspecialchars($user['noms_user']) ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Ajouter</button>
        </form>

        <h1>Modifier ou supprimer une créature existante</h1>
        <?php foreach ($creatures as $creature): ?>
            <form method="POST" action="modif_creature.php" enctype="multipart/form-data">
                <input type="hidden" name="id_creature" value="<?= htmlspecialchars($creature['id_creature']) ?>">

                <label for="nom-<?= $creature['id_creature'] ?>">Nom :</label>
                <input type="text" id="nom-<?= $creature['id_creature'] ?>" name="nom" value="<?= htmlspecialchars($creature['nom_creature']) ?>" required>

                <label for="image-<?= $creature['id_creature'] ?>">Image :</label>
                <input type="file" id="image-<?= $creature['id_creature'] ?>" name="image" accept="image/*">

                <label for="type-<?= $creature['id_creature'] ?>">Type :</label>
                <select id="type-<?= $creature['id_creature'] ?>" name="type" required>
                    <option value="1" <?= $creature['type_id'] == 1 ? 'selected' : '' ?>>Aquatique</option>
                    <option value="2" <?= $creature['type_id'] == 2 ? 'selected' : '' ?>>Démonique</option>
                    <option value="3" <?= $creature['type_id'] == 3 ? 'selected' : '' ?>>Mi-bête</option>
                    <option value="4" <?= $creature['type_id'] == 4 ? 'selected' : '' ?>>Mort vivant</option>
                </select>

                <label for="description-<?= $creature['id_creature'] ?>">Description :</label>
                <textarea id="description-<?= $creature['id_creature'] ?>" name="description" rows="4" required><?= htmlspecialchars($creature['description_creature']) ?></textarea>

                <label for="user-<?= $creature['id_creature'] ?>">Créateur :</label>
                <select id="user-<?= $creature['id_creature'] ?>" name="user_id" required>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= htmlspecialchars($user['user_id']) ?>" <?= $creature['user_id'] == $user['user_id'] ? 'selected' : '' ?>><?= htmlspecialchars($user['noms_user']) ?></option>
                    <?php endforeach; ?>
                </select>

                <div class="button-group">
                    <button type="submit">Modifier</button>
                    <button type="submit" formaction="delete-créature.php" id="delete-boutton" class="delete">Supprimer</button>
                </div>
                </form>
            </form>
            
        <?php endforeach; ?>
    </main>
</body>
</html>
