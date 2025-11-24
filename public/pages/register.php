<?php

session_start();

require __DIR__ . '/../src/utils/autoloader.php';
require __DIR__ . '/../src/i18n/Language.php';
$lang = Language::getInstance();

$message = '';
$messageType = '';


if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password']; 
    $passwordConfirm = $_POST['password_confirm'];

    if (empty($username) || empty($email) || empty($password) || empty($passwordConfirm)) {
        $message = "Veuillez remplir tous les champs.";
        $messageType = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Adresse e-mail invalide.";
        $messageType = 'error';
    } elseif ($password !== $passwordConfirm) {
        $message = "Les mots de passe ne correspondent pas.";
        $messageType = 'error';
    } else {
        try {
            $db = new Database();
            $pdo = $db->getPdo();

            
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);  
            $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
            
            if ($stmt->execute([
                'username' => $username, 
                'email' => $email, 
                'password' => $hashedPassword
            ])) {
              
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $username;
                
                header('Location: index.php'); 
                exit();
            } else {
                
                $message = "Erreur : Nom d'utilisateur ou e-mail déjà utilisé.";
                $messageType = 'error';
            }

        } catch (Exception $e) {

            $message = "Erreur système lors de l'inscription.";
            $messageType = 'error';
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

    <title>Inscription | <?= t('app_name') ?></title> 
</head>

<body>
    <main class="container">
        <hgroup>
            <h1>Inscription</h1>
            <h3>Créez votre compte pour commencer à suivre vos courses.</h3>
        </hgroup>

        <?php if (!empty($message)): ?>
            <article class="<?= $messageType === 'error' ? 'error' : 'success' ?>" 
                     style="background-color: <?= $messageType === 'error' ? '#f8d7da' : '#d4edda' ?>; 
                            color: <?= $messageType === 'error' ? '#721c24' : '#155724' ?>; 
                            padding: 1rem; border-radius: 5px;">
                <?= $message ?>
            </article>
        <?php endif; ?>

        <form method="post" action="">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" required 
                   value="<?= isset($username) ? htmlspecialchars($username) : '' ?>">

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required
                   value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">

            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>

            <label for="password_confirm">Confirmer le mot de passe</label>
            <input type="password" id="password_confirm" name="password_confirm" required>

            <button type="submit">S'inscrire</button>
        </form>

        <p><small>Vous avez déjà un compte ? <a href="login.php">Connectez-vous ici</a></small></p>
        
        <br>
        <button><a href="/../index.php"><?= t('back_to_home') ?></a></button>
    </main>

    <?php include __DIR__ . '/../src/i18n/language-footer.php'; ?>
</body>

</html>