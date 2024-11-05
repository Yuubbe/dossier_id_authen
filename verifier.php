<?php
if (isset($_GET['code'])) {
    // Connexion à la base de données
    $pdo = new PDO('mysql:host=localhost;dbname=site_inscription', 'root', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer le code de vérification depuis l'URL
    $code = $_GET['code'];

    // Vérifier si ce code existe dans la base de données
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE verification_code = :code");
    $stmt->execute([':code' => $code]);

    if ($stmt->rowCount() > 0) {
        // Activer l'utilisateur (mettre 'verified' à 1)
        $stmt = $pdo->prepare("UPDATE utilisateurs SET verified = 1 WHERE verification_code = :code");
        $stmt->execute([':code' => $code]);
        echo "Votre compte a été vérifié avec succès !";
    } else {
        echo "Code de vérification invalide.";
    }
}
?>
