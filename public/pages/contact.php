<?php
require __DIR__ . '/../../src/i18n/Language.php';
$lang = Language::getInstance();

// --- Partie PHP : traitement du formulaire ---
$messageEnvoye = false;
$erreur = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));


    if (!empty($nom) && !empty($email) && !empty($message)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Configuration de l'email
            $to = "votre-adresse@mail.com"; // <-- Mets ici ton adresse de réception
            $subject = "Nouveau message de contact de $nom";
            $body = "Nom : $nom\nEmail : $email\n\nMessage :\n$message";
            $headers = "From: $email";

            if (mail($to, $subject, $body, $headers)) {
                $messageEnvoye = true;
            } else {
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

    <?php include __DIR__ . '/../../src/i18n/language-footer.php'; ?>
</body>

</html>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<form id="contact" method="post" action="traitement_formulaire.php">
    <fieldset>
        <legend>Vos coordonnées</legend>
        <p><label for="nom">Nom :</label><input type="text" id="nom" name="nom" /></p>
        <p><label for="prenom">Prénom :</label><input type="text" id="prenom" name="prenom" /></p>
        <p><label for="email">Email :</label><input type="email" id="email" name="email" /></p>
        <p><label for="message">Votre demande</label><input type="text" id="message" name="message" /></p>
    </fieldset>

    <div><input type="submit" name="envoi" value="Envoyer le formulaire !" /></div>