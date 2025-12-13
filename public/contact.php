<?php

/**
 * Contact Page
 *
 * Provides contact form for users to send messages/inquiries.
 * Validates form input and sends email using PHP mail() function.
 *
 * @uses $_POST['nom'] Last name from contact form
 * @uses $_POST['prenom'] First name from contact form
 * @uses $_POST['email'] Email address from contact form
 * @uses $_POST['message'] Message text from contact form
 *
 * Security: Input sanitization, email validation
 * Access: Public (no authentication required)
 */
require __DIR__ . '/../src/i18n/Language.php';



require __DIR__ . '/../src/classes/PHPMailer/PHPMailer/Exception.php';
require __DIR__ . '/../src/classes/PHPMailer/PHPMailer/PHPMailer.php';
require __DIR__ . '/../src/classes/PHPMailer/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;



use RunTracker\I18n\Language;
use function RunTracker\I18n\t;
use function RunTracker\I18n\currentLang;

/** @var Language $lang Language instance for translations */
$lang = Language::getInstance();

/** @var bool $messageEnvoye Flag indicating if message was sent successfully */
$messageEnvoye = false;

/** @var string $erreur Error message to display */
$erreur = '';

/**
 * Process contact form submission
 * Validates input and sends email
 */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    /** @var string $nom Sanitized last name from form */
    $nom = htmlspecialchars(trim($_POST['nom']));

    /** @var string $prenom Sanitized first name from form */
    $prenom = htmlspecialchars(trim($_POST['prenom']));

    /** @var string $email Sanitized email address from form */
    $email = htmlspecialchars(trim($_POST['email']));

    /** @var string $message Sanitized message text from form */
    $message = htmlspecialchars(trim($_POST['message']));

    if (!empty($nom) && !empty($email) && !empty($message)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            
           
            $mail = new PHPMailer(true);

            try {
                
                $mail->isSMTP();
                $mail->Host       = 'mail.infomaniak.com'; 
                $mail->SMTPAuth   = true;
                
                
                $mail->Username   = 'contact@runtracker.ch'; 
                
                
                $mail->Password   = 'Salutpoilu99$'; 
           
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
                $mail->Port       = 465;

                
                $mail->CharSet = 'UTF-8';

                
                $mail->setFrom($mail->Username, 'Site RunTracker'); 
                
               
                $mail->addAddress('ykem99@gmail.com'); 
                
                
                $mail->addReplyTo($email, "$prenom $nom");

               
                $mail->isHTML(false); 
                $mail->Subject = "Nouveau message de contact de $nom";
                $mail->Body    = "Nom : $nom $prenom\nEmail : $email\n\nMessage :\n$message";

                $mail->send();
                $messageEnvoye = true;

            } catch (Exception $e) {
               
                $erreur = t('contact_error_send'); 
                
            }
          

        } else {
            $erreur = t('contact_error_email');
        }
    } else {
        $erreur = t('contact_error_fields');
    }
}
?>

<!DOCTYPE html>
<html lang="<?= currentLang() ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title><?= t('contact_title') ?> | <?= t('app_name') ?></title>
</head>

<body>
    <main class="container">
        <h1><?= t('contact_title') ?></h1>

        <?php if ($messageEnvoye): ?>
            <article style="background-color: #d4edda; color: #155724; padding: 1rem; border-radius: 5px;">
                <?= t('contact_success') ?>
            </article>
        <?php endif; ?>

        <?php if (!empty($erreur)): ?>
            <article style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 5px;">
                <?= $erreur ?>
            </article>
        <?php endif; ?>

        <form id="contact" method="post" action="">
            <fieldset>
                <legend><?= t('contact_your_info') ?></legend>
                <p>
                    <label for="nom"><?= t('contact_lastname') ?></label>
                    <input type="text" id="nom" name="nom" required />
                </p>
                <p>
                    <label for="prenom"><?= t('contact_firstname') ?></label>
                    <input type="text" id="prenom" name="prenom" />
                </p>
                <p>
                    <label for="email"><?= t('contact_email') ?></label>
                    <input type="email" id="email" name="email" required />
                </p>
                <p>
                    <label for="message"><?= t('contact_message') ?></label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </p>
            </fieldset>

            <div>
                <input type="submit" name="envoi" value="<?= t('contact_submit') ?>" />
            </div>
        </form>

        <br>
        <button><a href="./index.php"><?= t('back_to_home') ?></a></button>
    </main>

    <?php include __DIR__ . '/../src/i18n/language-footer.php'; ?>
</body>

</html>