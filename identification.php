<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Assurez-vous que PHPMailer est installé via Composer

include 'connexion_bd.php'; // Connexion à la base de données

$nom = $_POST['nom'];
$email = $_POST['email'];
$motdepasse = password_hash($_POST['motdepasse'], PASSWORD_BCRYPT);
$verification_code = random_int(100000, 999999); // Génère un code à 6 chiffres

// Insertion de l'utilisateur dans la base de données
$stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, motdepasse, verification_code) VALUES (?, ?, ?, ?)");
$stmt->execute([$nom, $email, $motdepasse, $verification_code]);

// Envoi de l'e-mail de vérification
$mail = new PHPMailer(true);
try {
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

    $mail->isHTML(true);
    $mail->Subject = 'Code de vérification pour votre compte';
    $mail->Body    = "<p>Bonjour $nom,</p>
                      <p>Votre code de vérification est : <strong>$verification_code</strong></p>
                      <p>Veuillez entrer ce code sur le site pour vérifier votre compte.</p>";
    $mail->AltBody = "Bonjour $nom, votre code de vérification est : $verification_code. Veuillez entrer ce code sur le site pour vérifier votre compte.";

    $mail->send();
    echo "Un code de vérification a été envoyé à votre adresse e-mail.";
} catch (Exception $e) {
    echo "L'e-mail n'a pas pu être envoyé. Erreur : {$mail->ErrorInfo}";
}
?>



