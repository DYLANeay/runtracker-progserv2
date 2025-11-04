<?php
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
            // Configuration de l’email
            $to = "votre-adresse@mail.com"; // <-- Mets ici ton adresse de réception
            $subject = "Nouveau message de contact de $nom";
            $body = "Nom : $nom\nEmail : $email\n\nMessage :\n$message";
            $headers = "From: $email";

            if (mail($to, $subject, $body, $headers)) {
                $messageEnvoye = true;
            } else {
                $erreur = "Erreur lors de l’envoi du message. Réessaie plus tard.";
            }
        } else {
            $erreur = "Adresse e-mail invalide.";
        }
    } else {
        $erreur = "Veuillez remplir tous les champs.";
    }
}
?>

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
 	<fieldset><legend>Vos coordonnées</legend>
 		<p><label for="nom">Nom :</label><input type="text" id="nom" name="nom" /></p>
 		<p><label for="prenom">Prénom :</label><input type="text" id="prenom" name="prenom" /></p>
 		<p><label for="email">Email :</label><input type="email" id="email" name="email" /></p>
        <p><label for="message">Votre demande</label><input type="textarea" id="message" name="message"/></p>
 	</fieldset>
  
 	<div><input type="submit" name="envoi" value="Envoyer le formulaire !" /></div>