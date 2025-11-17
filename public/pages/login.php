<?php
// 1. Démarrer la session au tout début (Théorie : page 5)
session_start();

require __DIR__ . '/../../src/utils/autoloader.php';
require __DIR__ . '/../../src/i18n/Language.php';
$lang = Language::getInstance();

$erreur = ''; // Variable pour stocker les messages d'erreur

// Vérifier si l'utilisateur est déjà authentifié
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// 2. Gérer la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupérer les données du formulaire
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password']; 

    if (empty($username) || empty($password)) {
        // Le message d'erreur est basé sur la logique de la page 18
        $erreur = "Veuillez entrer un nom d'utilisateur et un mot de passe.";
    } else {
        try {
            $db = new Database();
            $pdo = $db->getPdo();

            // 3. Récupérer l'utilisateur de la base de données (Théorie : page 17)
            $stmt = $pdo->prepare('SELECT id, username, password FROM users WHERE username = :username');
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // 4. Vérifier le mot de passe (Théorie : page 18)
            if ($user && password_verify($password, $user['password'])) {
                // Authentification réussie, stocker l'état de connexion dans la session (Théorie : page 18)

                // 5. Stocker les informations utilisateur dans la session (Théorie : page 18)
                $_SESSION['user_id'] = $user['id']; 
                $_SESSION['username'] = $user['username']; 

                // 6. Rediriger vers la page d'accueil (Théorie : page 18)
                header('Location: index.php'); 
                exit();
            } else {
                // Authentification échouée (Théorie : page 18)
                $erreur = "Nom d'utilisateur ou mot de passe incorrect.";
            }

        } catch (Exception $e) {
            // Gérer une erreur de base de données
            $erreur = "Une erreur est survenue lors de la connexion à la base de données.";
        }
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

    <title>Connexion | <?= t('app_name') ?></title> 
</head>

<body>
    <main class="container">
        <hgroup>
            <h1>Connexion</h1>
            <h3>Veuillez entrer vos identifiants pour accéder à RunTracker.</h3>
        </hgroup>

        <?php if (!empty($erreur)): ?>
            <article class="error" style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 5px;">
                <?= $erreur ?>
            </article>
        <?php endif; ?>

        <form method="post" action="">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Se connecter</button>
        </form>

        <p><small>Pas encore de compte ? <a href="register.php">Inscrivez-vous ici</a></small></p>
        
        <br>
        <button><a href="../index.php"><?= t('back_to_home') ?></a></button>
    </main>

    <?php include __DIR__ . '/../../src/i18n/language-footer.php'; ?>
</body>

</html>