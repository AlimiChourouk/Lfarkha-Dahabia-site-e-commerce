<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../libs/PHPMailer/src/Exception.php';
require __DIR__ . '/../libs/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../libs/PHPMailer/src/SMTP.php';

class EmailService {
    private static function getMailer() {
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'chouroukalimi@gmail.com';
            $mail->Password = 'cxup jtik vacf iacp';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Sender and reply-to
            $mail->setFrom('no-reply@lfarkhadahabia.com', 'Lfarkha Dahabia');
            $mail->addReplyTo('contact@lfarkhadahabia.com', 'Lfarkha Dahabia');

            // Log SMTP configuration
            error_log("PHPMailer SMTP config - Host: {$mail->Host}, Port: {$mail->Port}, Username: {$mail->Username}");

            return $mail;
        } catch (Exception $e) {
            error_log("PHPMailer setup failed: {$mail->ErrorInfo}");
            throw $e;
        }
    }

    public static function sendOrderConfirmationEmail($to, $orderId, $produits, $total, $adresse, $status) {
        error_log("Attempting to send confirmation email to: $to for order #$orderId");
        
        try {
            $mail = self::getMailer();
            $mail->addAddress($to);
            $mail->isHTML(false);
            $mail->Subject = "Confirmation de votre commande #$orderId - Lfarkha Dahabia";

            // Compose email body
            $message = "Cher(e) client(e),\n\n";
            $message .= "Merci pour votre commande sur Lfarkha Dahabia ! Voici les détails de votre commande :\n\n";
            $message .= "Commande #$orderId\n";
            $message .= "Statut : $status\n";
            $message .= "Adresse de livraison : $adresse\n\n";
            $message .= "Articles commandés :\n";
            $message .= "----------------------------------------\n";
            
            foreach ($produits as $produit) {
                $subtotal = $produit['QTE'] * $produit['prix'];
                $message .= "Produit : " . htmlspecialchars($produit['nomProduit']) . "\n";
                $message .= "Quantité : " . $produit['QTE'] . "\n";
                $message .= "Prix unitaire : " . number_format($produit['prix'], 2) . " DH\n";
                $message .= "Sous-total : " . number_format($subtotal, 2) . " DH\n";
                $message .= "----------------------------------------\n";
            }
            
            $message .= "\nTotal de la commande : " . number_format($total, 2) . " DH\n\n";
            $message .= "Nous vous tiendrons informé(e) de l'état de votre commande.\n";
            $message .= "Pour toute question, contactez-nous à contact@lfarkhadahabia.com ou au +212 123 456 789.\n\n";
            $message .= "Cordialement,\nL'équipe Lfarkha Dahabia";

            $mail->Body = $message;

            $mail->send();
            error_log("Confirmation email sent successfully to $to for order #$orderId");
            return true;
        } catch (Exception $e) {
            error_log("Failed to send confirmation email to $to for order #$orderId: {$mail->ErrorInfo}");
            return false;
        }
    }

    public static function sendOrderStatusUpdateEmail($to, $orderId, $newStatus) {
        error_log("Attempting to send status update email to: $to for order #$orderId, new status: $newStatus");
        
        try {
            $mail = self::getMailer();
            $mail->addAddress($to);
            $mail->isHTML(false);
            $mail->Subject = "Mise à jour de votre commande #$orderId - Lfarkha Dahabia";

            // Compose email body
            $message = "Cher(e) client(e),\n\n";
            $message .= "Nous vous informons que l'état de votre commande #$orderId a été mis à jour.\n\n";
            $message .= "Nouveau statut : $newStatus\n\n";
            $message .= "Pour toute question, contactez-nous à contact@lfarkhadahabia.com ou au +212 123 456 789.\n\n";
            $message .= "Cordialement,\nL'équipe Lfarkha Dahabia";

            $mail->Body = $message;

            $mail->send();
            error_log("Status update email sent successfully to $to for order #$orderId");
            return true;
        } catch (Exception $e) {
            error_log("Failed to send status update email to $to for order #$orderId: {$mail->ErrorInfo}");
            return false;
        }
    }
}
?>