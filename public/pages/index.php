<?php

// DOIT ÊTRE LA PREMIÈRE INSTRUCTION
session_start(); // <-- AJOUTEZ CETTE LIGNE

require __DIR__ . '/../../src/utils/autoloader.php';
require __DIR__ . '/../../src/i18n/Language.php';
$lang = Language::getInstance();
// Page de dashboard ou page d'accueil après connexion

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
        <h1><?= t('dashboard_title') ?></h1>

        <h3><?= t('dashboard_welcome') ?></h3>

        <h4><?= t('home_tagline') ?></h4>


        <button><a href="./progress.php"><?= t('dashboard_view_progress') ?></a></button>
        <button><a href="./contact.php"><?= t('dashboard_contact_us') ?></a></button>

        <button><a href="./create.php"><?= t('dashboard_add_run') ?></a></button>

    </main>

    <?php include __DIR__ . '/../../src/i18n/language-footer.php'; ?>
</body>

</html>