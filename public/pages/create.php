<?php
session_start();
require __DIR__ . '/../../src/utils/autoloader.php';
require __DIR__ . '/../../src/i18n/Language.php';
$lang = Language::getInstance();

// Page de création d'une nouvelle course
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "Le contenu de la variable `\$_POST` est : ";
    var_dump($_POST);
    $time = $_POST["time"];
    $distance = $_POST["distance"];
    $notes = $_POST["notes"];

    $errors = [];
    if (empty($name)) {
        // On ajoute un message d'erreur au tableau
        array_push($errors, "Le nom est obligatoire.");
    }

    if (strlen($name) < 2) {
        // On ajoute un message d'erreur au tableau
        array_push($errors, "Le nom doit contenir au moins 2 caractères.");
    }

    if (empty($species)) {
        // On ajoute un message d'erreur au tableau
        array_push($errors, "L'espèce est obligatoire.");
    }

    if (empty($sex)) {
        // On ajoute un message d'erreur au tableau
        array_push($errors, "Le sexe est obligatoire.");
    }

    if (empty($age)) {
        // On ajoute un message d'erreur au tableau
        array_push($errors, "L'âge est obligatoire.");
    }

    if (!is_numeric($age) || $age < 0) {
        // On ajoute un message d'erreur au tableau
        array_push($errors, "L'âge doit être un nombre entier positif.");
    }

    if (!empty($size) && (!is_numeric($size) || $size < 0)) {
        // On ajoute un message d'erreur au tableau
        array_push($errors, "La taille doit être un nombre entier positif.");
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

    <title><?= t('create_title') ?> | <?= t('app_name') ?></title>
</head>

<body>
    <main class="container">
        <h1><?= t('create_welcome') ?></h1>

        <h2><?= t('create_tagline') ?></h2>

        <button><a href="./index.php"><?= t('create_add_run') ?></a></button>
        <button><a href="./index.php"><?= t('create_view_dashboard') ?></a></button>

        <button><a href="./progress.php"><?= t('create_view_progress') ?></a></button>

        <label for="notes"><?= t('create_notes') ?></label><br>
        <textarea id="notes" name="notes" rows="4" cols="50"><?php if (isset($notes)) echo $notes; ?></textarea>

        <br>

        <button type="submit"><?= t('create_submit') ?></button><br>
        <button type="reset"><?= t('create_reset') ?></button>

    </main>

    <?php include __DIR__ . '/../../src/i18n/language-footer.php'; ?>
</body>

</html>