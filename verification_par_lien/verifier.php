<?php
include 'connexion_bd.php'; // Connexion à la base de données

// Récupération du code de vérification depuis l'URL
$verification_code = $_GET['code'] ?? '';

if ($verification_code) {
    // Vérification du code dans la base de données
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE verification_code = ?");
    $stmt->execute([$verification_code]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Mise à jour pour marquer le compte comme vérifié
        $update_stmt = $pdo->prepare("UPDATE utilisateurs SET verified = 1 WHERE verification_code = ?");
        $update_stmt->execute([$verification_code]);

        echo "Votre compte a été vérifié avec succès.";
    } else {
        echo "Code de vérification invalide ou déjà utilisé.";
    }
} else {
    echo "Aucun code de vérification fourni.";
}
?>
<br>
<a href="login.html">Se connecter !</a>