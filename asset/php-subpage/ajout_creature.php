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

// Vérification si les données POST sont reçues
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $type = $_POST['type'];
    $description = htmlspecialchars($_POST['description']);
    $user_id = $_POST['user_id'];

    // Gestion de l'image
    $image = null;
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "asset/img/uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $image = $target_file;
    }

    try {
        // Préparer la requête SQL
        $query = "INSERT INTO creature (nom_creature, img_creature, type_id, description_creature, user_id) VALUES (?, ?, ?, ?, ?)";
        $params = [$nom, $image, $type, $description, $user_id];

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        header("Location: édit_créature.php?success=1");
        exit;
    } catch (PDOException $e) {
        die("Erreur lors de l'ajout : " . $e->getMessage());
    }
} else {
    header("Location: édit_créature.php?error=1");
    exit;
}
?>
