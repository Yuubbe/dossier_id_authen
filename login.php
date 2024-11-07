<?php
session_start();
include 'connexion_bd.php'; // Connexion à la base de données

$email = $_POST['email'];
$motdepasse = $_POST['motdepasse'];

// Recherche de l'utilisateur dans la base de données
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($motdepasse, $user['motdepasse'])) {
    if ($user['verified'] == 1) {
        // Connexion réussie : démarrer une session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nom'];
        echo "Connexion réussie ! Bienvenue, " . htmlspecialchars($user['nom']) . ".";
    } else {
        echo "Votre compte n'est pas vérifié. Veuillez vérifier votre e-mail.";
    }
} else {
    echo "E-mail ou mot de passe incorrect.";
}
?>
