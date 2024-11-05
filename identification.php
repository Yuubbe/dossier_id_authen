<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try {
    // Connexion à la base de données MySQL
    $pdo = new PDO('mysql:host=localhost;dbname=inscription', 'root', 'root');
    
    // Activation du mode d'erreur pour PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connexion réussie à la base de données!";
    
} catch (PDOException $e) {
    // Gestion de l'erreur de connexion
    echo "Erreur de connexion : " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $motdepasse = password_hash($_POST['motdepasse'], PASSWORD_BCRYPT);  // Hachage du mot de passe
    
    // Générer un code de vérification unique
    $verification_code = bin2hex(random_bytes(16));  // Code de 32 caractères aléatoires

    // Insérer l'utilisateur dans la base de données
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, motdepasse, verification_code) 
                           VALUES (:nom, :email, :motdepasse, :verification_code)");
    $stmt->execute([
        ':nom' => $nom,
        ':email' => $email,
        ':motdepasse' => $motdepasse,
        ':verification_code' => $verification_code
    ]);

    // Envoi de l'email de vérification
    envoyerEmailVerification($email, $verification_code);
    
    echo "Inscription réussie. Un e-mail de vérification vous a été envoyé.";
}

function envoyerEmailVerification($email, $verification_code) {
    $mail = new PHPMailer(true);  // Crée une nouvelle instance de PHPMailer

    try {
        // Paramétrage de PHPMailer pour utiliser le serveur SMTP d'Infomaniak
        $mail->isSMTP();  // Utilise SMTP
        $mail->Host = 'smtp.infomaniak.ch';  // Serveur SMTP d'Infomaniak
        $mail->SMTPAuth = true;  // Authentification SMTP activée
        $mail->Username = 'gamblinvincent@ik.me';  // Votre adresse e-mail Infomaniak
        $mail->Password = 'mapjy3-Hoczoc-sahsuz';  // Mot de passe de votre compte Infomaniak
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Utilisation de STARTTLS
        $mail->Port = 587;  // Port pour STARTTLS (ou utilisez 465 pour SSL)

        // Destinataire et expéditeur
        $mail->setFrom('gamblinvincent@ik.me', 'verification@gmail.com');  // Adresse e-mail d'envoi
        $mail->addAddress($email);  // Adresse e-mail du destinataire

        // Sujet et contenu du message
        $mail->Subject = 'Vérification de votre inscription';
        $mail->Body    = "Bonjour, \n\nMerci de vous être inscrit. Cliquez sur le lien ci-dessous pour vérifier votre adresse e-mail :\n\n";
        $mail->Body .= "http://example.com/verifier.php?code=$verification_code";

        // Envoi de l'email
        $mail->send();
        echo 'L\'e-mail a été envoyé avec succès.';
    } catch (Exception $e) {
        echo "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
    }
}

?>



