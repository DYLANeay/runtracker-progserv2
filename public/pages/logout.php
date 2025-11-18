<?php
// 1. Démarrer la session : Nécessaire pour pouvoir accéder et détruire la session existante.
session_start();

// 2. Vider le tableau $_SESSION : C'est une bonne pratique pour supprimer toutes les variables de session.
$_SESSION = array();

// 3. Détruire la session (Théorie : page 8)
session_destroy();

// 4. Rediriger l'utilisateur vers la page d'accueil.
// J'utilise le chemin de base du projet (/runtracker-progserv2/) qui est la racine publique (public/index.php)
header('Location: /runtracker-progserv2/index.php'); 
exit();
?>