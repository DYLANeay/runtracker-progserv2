
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP; 

const MAIL_CONFIGURATION_FILE = __DIR__ . '/../config/mail.ini';


function sendWelcomeEmail(string $recipientEmail, string $username): bool
{
    
    if (!file_exists(MAIL_CONFIGURATION_FILE)) {
        error_log("Fichier de configuration mail.ini manquant.");
        return false;
    }
    $config = parse_ini_file(MAIL_CONFIGURATION_FILE);
    if ($config === false) {
          error_log("Erreur de lecture du fichier de configuration mail.ini.");
          return false;
    }

    $mail = new PHPMailer(true);

    try {
        
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; 
        $mail->Debugoutput = 'html'; 
       
        
        $mail->isSMTP();
        $mail->Host       = $config['host'];
        $mail->Port       = $config['port'];
        $mail->SMTPAuth   = (bool)$config['authentication'];
        
        if ($mail->SMTPAuth) {
            $mail->Username = $config['username'];
            $mail->Password = $config['password'];
            
            $mail->SMTPSecure = ($config['port'] == 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        }

     
        $appName = $config['from_name'] ?? 'Runtracker'; 
        $mail->setFrom($config['from_email'], $appName);
        $mail->addAddress($recipientEmail);
        $mail->CharSet = "UTF-8"; 

        
       
        $mail->isHTML(true);
        $mail->Subject = "Félicitations, votre compte sur {$appName} est créé !";
        
        $mail->Body    = "<h1>Bienvenue, $username !</h1>
                          <p>Toutes nos félicitations, votre compte sur <strong>{$appName}</strong> a été créé avec succès.</p>
                          <p>Vous pouvez maintenant vous connecter et profiter de toutes nos fonctionnalités.</p>
                          <p>À bientôt sur {$appName} !</p>";
                          
        $mail->AltBody = "Félicitations, $username ! Votre compte sur {$appName} est créé. Vous pouvez vous connecter.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        
        error_log("Échec de l'envoi de l'e-mail de bienvenue. Erreur PHPMailer: {$mail->ErrorInfo}");
        return false;
    }
}