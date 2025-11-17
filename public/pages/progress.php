<?php
session_start();
require __DIR__ . '/../../src/utils/autoloader.php';
require __DIR__ . '/../../src/i18n/Language.php';
$lang = Language::getInstance();

// Here comes the js code to style data into graphs, ...

?>



<!DOCTYPE html>
<html lang="<?= currentLang() ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">

    <title><?= t('progress_title') ?> | <?= t('app_name') ?></title>
</head>

<body>
    <main class="container">
        <h1><?= t('progress_title') ?></h1>

        <h3><?= t('progress_welcome') ?></h3>
        <button><a href="./index.php"><?= t('back_to_home') ?></a></button>

        <h4></h4>

    </main>

    <?php include __DIR__ . '/../../src/i18n/language-footer.php'; ?>
</body>

</html>