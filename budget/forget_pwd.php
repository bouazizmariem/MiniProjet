<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Inclure PHPMailer avec Composer

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Adresse e-mail invalide.";
        exit;
    }

    $reset_link = "https://savemoney.com/reset_pwd.php?email=" . urlencode($email) . "&token=" . bin2hex(random_bytes(16));

    $mail = new PHPMailer(true);

    try {
        // Paramètres SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Utiliser le serveur SMTP de Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'benjdidiamariem6@gmail.com'; // Votre adresse Gmail
        $mail->Password = 'mariem10bj#'; // Le mot de passe de votre Gmail
        $mail->SMTPSecure = 'tls'; // Sécurité TLS
        $mail->Port = 587;

        // Paramètres de l'e-mail
        $mail->setFrom('benjdidiamariem6@gmail.com', 'SaveMoney');
        $mail->addAddress($email);
        $mail->Subject = "Réinitialisation de votre mot de passe";
        $mail->Body = "Cliquez sur le lien suivant pour réinitialiser votre mot de passe : \n" . $reset_link;

        // Envoyer l'e-mail
        $mail->send();
        echo "Un lien de réinitialisation a été envoyé à votre adresse e-mail.";
    } catch (Exception $e) {
        echo "Erreur lors de l'envoi de l'e-mail : {$mail->ErrorInfo}";
    }
}
?>
