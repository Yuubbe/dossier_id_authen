<?php
include 'connexion_bd.php'; // Connexion à la base de données

$email = $_POST['email'];
$verification_code = $_POST['verification_code'];

// Vérification du code de vérification
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ? AND verification_code = ?");
$stmt->execute([$email, $verification_code]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // Mise à jour pour marquer le compte comme vérifié
    $update_stmt = $pdo->prepare("UPDATE utilisateurs SET verified = 1 WHERE email = ?");
    $update_stmt->execute([$email]);

    echo "Votre compte a été vérifié avec succès.";
} else {
    echo "Code de vérification incorrect. Veuillez réessayer.";
}
?>
