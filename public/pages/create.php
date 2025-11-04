<?php

require __DIR__ . '/../../src/utils/autoloader.php';
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
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">

    <title>Ajouter une course | RunTracker</title>
</head>

<body>
    <main class="container">
        <h1>Bienvenue sur la page de création d'une nouvelle course de l'application web RunTracker.</h1>
        
        <h2>L'application parfaite pour suivre votre progression en course à pied</h2>

        
        <button><a href="./index.php">Voir mon dashboard</a></button>

        <button><a href="./progress.php">Voir ma progression</a></button>
            <br>

        <label for="distance">Distance(en kilomètres) :</label><br>
        <input type="number" id="distance" name="distance" value="<?php if (isset($distance)) echo $distance; ?>" min="0" step="0.1" />

        <br>
        
          <br>

        <label for="time">Temps(en minutes) :</label><br>
        <input type="number" id="time" name="time" value="<?php if (isset($time)) echo $time; ?>" min="0" step="0.1" />

        <br>
        
        <label for="notes">Notes :</label><br>
        <textarea id="notes" name="notes" rows="4" cols="50"><?php if (isset($notes)) echo $notes; ?></textarea>

        <br>

        <button type="submit">Créer</button><br>
        <button type="reset">Réinitialiser</button>

    </main>
</body>

</html>