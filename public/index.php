<?php

/**
 * Dashboard Page
 *
 * Main landing page for authenticated users. Displays the user dashboard
 * with navigation options to view progress, add new runs, and contact support.
 *
 * @uses $_SESSION['user_id'] User ID from session
 * @uses $_SESSION['username'] Username from session
 *
 * Security: Requires authenticated user session
 * Access: Authenticated users only
 */

session_start();

/**
 * Check if user is authenticated
 * Redirects to login page if no valid session exists
 */
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require __DIR__ . '/../src/utils/autoloader.php';
require __DIR__ . '/../src/i18n/Language.php';

/** @var Language $lang Language instance for translations */
$lang = Language::getInstance();

/** @var string $username Current logged-in user's username */
$username = $_SESSION['username'] ?? 'Utilisateur';


?>

<!DOCTYPE html>
<html lang="<?= currentLang() ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">

    <title><?= t('dashboard_title') ?> | <?= t('app_name') ?></title>
</head>

<body>
    <main class="container">
        <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1><?= t('dashboard_title') ?></h1>
            <nav>
                <ul>
                    <li><strong><?= t('app_name') ?></strong></li>
                </ul>
                <ul>
                    <li>
                        Bienvenue, <strong><?= htmlspecialchars($username) ?></strong> !
                    </li>
                    <li>
                        <a href="./logout.php" role="button" class="secondary">
                            Déconnexion
                        </a>
                    </li>
                </ul>
            </nav>
        </header>


        <h3><?= t('dashboard_welcome') ?></h3>

        <h4><?= t('home_tagline') ?></h4>


        <button><a href="./progress.php"><?= t('dashboard_view_progress') ?></a></button>
        <button><a href="./contact.php"><?= t('dashboard_contact_us') ?></a></button>

        <button><a href="./create.php"><?= t('dashboard_add_run') ?></a></button>

    </main>

    <?php include __DIR__ . '/../src/i18n/language-footer.php'; ?>
</body>

</html>