<?php
session_start();

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

// Traitement des formulaires
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nom'], $_POST['mot_de_passe'])) {
        if (!isset($_POST['confirm_mot_de_passe'])) {
            // Connexion
            $nom = htmlspecialchars($_POST['nom']);
            $mot_de_passe = $_POST['mot_de_passe'];

            try {
                $stmt = $pdo->prepare("SELECT user_id, mot_de_pass FROM user WHERE noms_user = ?");
                $stmt->execute([$nom]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($mot_de_passe, $user['mot_de_pass'])) {
                    $_SESSION['user_id'] = $user['user_id'];
                    header("Location: home.php");
                    exit;
                } else {
                    echo "Nom d'utilisateur ou mot de passe incorrect.";
                }
            } catch (PDOException $e) {
                echo "Erreur lors de la connexion : " . $e->getMessage();
            }
        } else {
            // Inscription
            $nom = htmlspecialchars($_POST['nom']);
            $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_ARGON2I);
            $confirm_mot_de_passe = $_POST['confirm_mot_de_passe'];
            $element = htmlspecialchars($_POST['element']);
            $second_element = !empty($_POST['second_element']) ? htmlspecialchars($_POST['second_element']) : null;

            try {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE noms_user = ?");
                $stmt->execute([$nom]);
                $user_exists = $stmt->fetchColumn();

                if ($user_exists > 0) {
                    echo "Nom d'utilisateur déjà pris. Veuillez en choisir un autre.";
                } elseif ($_POST['mot_de_passe'] === $confirm_mot_de_passe) {
                    try {
                        $pdo->beginTransaction();

                        $stmt = $pdo->prepare("INSERT INTO user (noms_user, mot_de_pass, element_id) VALUES (?, ?, (SELECT element_id FROM element WHERE noms_element = ?))");
                        $stmt->execute([$nom, $mot_de_passe, $element]);

                        $user_id = $pdo->lastInsertId();

                        if ($second_element) {
                            $stmt = $pdo->prepare("INSERT INTO user_element (user_id, element_id) VALUES (?, (SELECT element_id FROM element WHERE noms_element = ?))");
                            $stmt->execute([$user_id, $second_element]);
                        }

                        $pdo->commit();
                        echo "Inscription réussie. Vous pouvez maintenant vous connecter.";
                    } catch (PDOException $e) {
                        $pdo->rollBack();
                        echo "Erreur lors de l'inscription : " . $e->getMessage();
                    }
                } else {
                    echo "Les mots de passe ne correspondent pas.";
                }
            } catch (PDOException $e) {
                echo "Erreur lors de la vérification du nom d'utilisateur : " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion / Inscription - Académie de Fordhiver</title>
    <link rel="stylesheet" href="asset/css/style_index.css">
</head>
<body>
    <header>
        <h1>Connexion / Inscription à l'Académie de Fordhiver</h1>
    </header>
    <main>
        <section style="display: flex; justify-content: space-around;">
            <!-- Formulaire de connexion -->
            <form method="POST">
                <h2>Connexion</h2>
                <label for="login-nom">Nom :</label>
                <input type="text" id="login-nom" name="nom" required>
                <br>
                <label for="login-mot-de-passe">Mot de passe :</label>
                <input type="password" id="login-mot-de-passe" name="mot_de_passe" required>
                <br>
                <button type="submit">Se connecter</button>
            </form>

            <!-- Formulaire d'inscription -->
            <form method="POST">
                <h2>Inscription</h2>
                <label for="signup-nom">Nom :</label>
                <input type="text" id="signup-nom" name="nom" required>
                <br>
                <label for="signup-mot-de-passe">Mot de passe :</label>
                <input type="password" id="signup-mot-de-passe" name="mot_de_passe" required>
                <br>
                <label for="signup-confirm-mot-de-passe">Confirmer le mot de passe :</label>
                <input type="password" id="signup-confirm-mot-de-passe" name="confirm_mot_de_passe" required>
                <br>
                <label for="signup-element">Élément :</label>
                <select id="signup-element" name="element" required>
                    <option value="air">Air</option>
                    <option value="eau">Eau</option>
                    <option value="feu">Feu</option>
                    <option value="lumière">Lumière</option>
                </select>
                <br>
                <label for="signup-second-element">Second Élément (optionnel) :</label>
                <select id="signup-second-element" name="second_element">
                    <option value="">Aucun</option>
                    <option value="air">Air</option>
                    <option value="eau">Eau</option>
                    <option value="feu">Feu</option>
                    <option value="lumière">Lumière</option>
                </select>
                <br>
                <button type="submit">S'inscrire</button>
            </form>
        </section>
    </main>
</body>
</html>
