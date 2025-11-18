<?php
require __DIR__ . '/src/i18n/Language.php';
$lang = Language::getInstance();
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="<?= currentLang() ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <title><?= t('home_title') ?> | <?= t('app_name') ?></title>
</head>

<body>
    <main class="container">
        <h1><?= t('home_title') ?></h1>

        <h3><?= t('home_welcome') ?></h3>

        <h4><?= t('home_tagline') ?></h4>

<?php if ($isLoggedIn): ?>
            <p><a href="./pages/index.php"><button><?= t('home_go_to_dashboard') ?></button></a></p>
            <p><a href="./pages/logout.php"><button>Se déconnecter</button></a></p>
        <?php else: ?>
            <div style="display: flex; gap: 1rem;">
                <a href="./public/pages/login.php" role="button" style="margin: 0;">Se connecter</a>
                
                <a href="./public/pages/register.php" role="button" style="margin: 0;">Inscription</a>
            </div>
        <?php endif; ?>
    </main>

    <?php include __DIR__ . '/src/i18n/language-footer.php'; ?>
</body>

</html>