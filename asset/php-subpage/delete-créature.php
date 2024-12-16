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

// Vérification de l'utilisateur admin
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT noms_element FROM user u JOIN element e ON u.element_id = e.element_id WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user_element = $stmt->fetchColumn();
    if ($user_element !== 'administrateur') {
        header("Location: home.php");
        exit;
    }
} catch (PDOException $e) {
    die("Erreur lors de la vérification : " . $e->getMessage());
}

// Suppression de la créature
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_creature'])) {
        $id_creature = $_POST['id_creature'];

        try {
            // Suppression de l'image associée si elle existe
            $stmt = $pdo->prepare("SELECT img_creature FROM creature WHERE id_creature = ?");
            $stmt->execute([$id_creature]);
            $image = $stmt->fetchColumn();

            if ($image && file_exists($image)) {
                unlink($image); 
            }

            // Suppression de la créature dans la base de données
            $stmt = $pdo->prepare("DELETE FROM creature WHERE id_creature = ?");
            $stmt->execute([$id_creature]);

            header("Location: édit_créature.php?success=delete");
            exit;
        } catch (PDOException $e) {
            die("Erreur lors de la suppression : " . $e->getMessage());
        }
    } else {
        header("Location: édit_créature.php?error=missing_id");
        exit;
    }
} else {
    header("Location: édit_créature.php");
    exit;
}
?>
