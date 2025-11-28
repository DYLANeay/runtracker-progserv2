<?php

session_start();


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require __DIR__ . '/../src/utils/autoloader.php';
require __DIR__ . '/../src/i18n/Language.php';
$lang = Language::getInstance();


$username = $_SESSION['username'] ?? 'Utilisateur';
$user_id = $_SESSION['user_id'];
$message = '';
$messageType = '';
$errors = [];


$date = $distance = $duration_str = $notes = '';
$pace = '00:00:00'; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    
    $pace_seconds = 0;
    
    $date = htmlspecialchars(trim($_POST["date"] ?? ''));
    $distance = floatval($_POST["distance"] ?? 0);
    $duration_str = htmlspecialchars(trim($_POST["duration"] ?? ''));
    $notes = htmlspecialchars(trim($_POST["notes"] ?? ''));
    

    if (empty($date)) {
        $errors[] = "La date est obligatoire.";
    }
    if ($distance <= 0) {
        $errors[] = "La distance doit être un nombre positif (en km).";
    }
    if (empty($duration_str)) {
        $errors[] = "La durée est obligatoire (format HH:MM:SS).";
    }
  
    if (!preg_match('/^(\d{2}):(\d{2}):(\d{2})$/', $duration_str) && !empty($duration_str)) {
        $errors[] = "Le format de la durée doit être HH:MM:SS.";
    }

   
    if (empty($errors)) {
        try {
            $db = new Database(); 
            $pdo = $db->getPdo();
            
        
            list($hours, $minutes, $seconds) = sscanf($duration_str, '%d:%d:%d');
            $total_seconds = $hours * 3600 + $minutes * 60 + $seconds;
            
            
            $pace_seconds = round($total_seconds / $distance);
            
           
            $pace = sprintf('%02d:%02d:%02d', floor($pace_seconds / 3600), floor(($pace_seconds / 60) % 60), $pace_seconds % 60);

            
            $stmt = $pdo->prepare('
                INSERT INTO runs (user_id, date, distance, duration, pace, notes)
                VALUES (:user_id, :date, :distance, :duration, :pace, :notes)
            ');
            
            $stmt->execute([
                'user_id' => $user_id, 
                'date' => $date, 
                'distance' => $distance, 
                'duration' => $duration_str,
                'pace' => $pace,
                'notes' => $notes
            ]);

            $message = "Course ajoutée avec succès ! L'allure calculée est : $pace/km.";
            $messageType = 'success';
            
            
            $date = $distance = $duration_str = $notes = '';

        } catch (Exception $e) {
           
            $errors[] = "Erreur lors de l'enregistrement de la course. (Détail: " . $e->getMessage() . ")";
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

    <title><?= t('create_title') ?> | <?= t('app_name') ?></title>
</head>

<body>
    <header class="container">
        <nav>
            <ul>
                <li><strong><?= t('app_name') ?></strong></li>
            </ul>
            <ul>
                <li>Bienvenue, <strong><?= htmlspecialchars($username) ?></strong> !</li>
                <li><a href="./logout.php" role="button" class="secondary">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    
    <main class="container">
        <h1><?= t('create_title') ?></h1>
        
        <?php if (!empty($message)): ?>
            <article class="success" style="background-color: #d4edda; color: #155724; padding: 1rem; border-radius: 5px;">
                <?= $message ?>
            </article>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <article class="error" style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 5px;">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </article>
        <?php endif; ?>

        <form method="post" action="./create.php">
            
            <fieldset>
                <legend><?= t('create_welcome') ?></legend>

                <label for="date">Date de la course:</label>
                <input type="date" id="date" name="date" required value="<?= htmlspecialchars($date) ?>">

                <label for="distance">Distance (km):</label>
                <input type="number" step="0.01" min="0.1" id="distance" name="distance" required value="<?= htmlspecialchars($distance) ?>">

                <label for="duration">Durée (HH:MM:SS):</label>
                <input type="text" id="duration" name="duration" pattern="(\d{2}):(\d{2}):(\d{2})" placeholder="Ex: 00:30:00" required value="<?= htmlspecialchars($duration_str) ?>">

                <label for="notes"><?= t('create_notes') ?></label>
                <textarea id="notes" name="notes" rows="4" cols="50"><?= htmlspecialchars($notes) ?></textarea>

                <br>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit"><?= t('create_submit') ?></button>
                    <button type="reset" class="secondary"><?= t('create_reset') ?></button>
                </div>
            </fieldset>
        </form>

        <br>
        
        <button><a href="./index.php" role="button" class="secondary"><?= t('create_view_dashboard') ?></a></button>
        <button><a href="./progress.php" role="button" class="secondary"><?= t('create_view_progress') ?></a></button>

    </main>

    <?php include __DIR__ . '/../src/i18n/language-footer.php'; ?>
</body>

</html>