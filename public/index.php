<?php
require __DIR__ . '/../src/i18n/Language.php';
$lang = Language::getInstance();
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


        <p><a href="./pages/index.php"><button><?= t('home_go_to_dashboard') ?></button></a></p>
    </main>

    <?php include __DIR__ . '/../src/i18n/language-footer.php'; ?>
</body>

</html>