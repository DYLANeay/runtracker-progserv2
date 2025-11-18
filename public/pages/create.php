<?php
// Démarrer la session au tout début
session_start();


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require __DIR__ . '/../../src/utils/autoloader.php';
require __DIR__ . '/../../src/i18n/Language.php';
$lang = Language::getInstance();

$username = $_SESSION['username'] ?? 'Utilisateur';
$user_id = $_SESSION['user_id'];
$runs = [];
$error_message = '';

try {
    $db = new Database();
    $pdo = $db->getPdo();

   
    $stmt = $pdo->prepare('
        SELECT 
            id, date, distance, duration, pace, notes
        FROM 
            runs
        WHERE 
            user_id = :user_id
        ORDER BY 
            date DESC
    ');
    
    $stmt->execute(['user_id' => $user_id]);
    
    // Récupérer tous les résultats sous forme de tableau associatif
    $runs = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    // En cas d'erreur de connexion ou de requête
    $error_message = "Erreur lors de la récupération des données : " . $e->getMessage();
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

    <title><?= t('progress_title') ?> | <?= t('app_name') ?></title>
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
        <h1><?= t('progress_title') ?></h1>
        
        <?php if (!empty($error_message)): ?>
            <article class="error" style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 5px;">
                <?= $error_message ?>
            </article>
        <?php endif; ?>

        <h2>Vos Courses Enregistrées</h2>

        <?php if (empty($runs)): ?>
            <p>Vous n'avez pas encore enregistré de course. <a href="./create.php">Enregistrez votre première course ici.</a></p>
        <?php else: ?>
            
            <figure>
            <table role="grid">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Distance (km)</th>
                        <th>Durée</th>
                        <th>Allure (/km)</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($runs as $run): ?>
                        <tr>
                            <td><?= htmlspecialchars($run['date']) ?></td>
                            <td><?= htmlspecialchars($run['distance']) ?></td>
                            <td><?= htmlspecialchars($run['duration']) ?></td>
                            <td><?= htmlspecialchars($run['pace']) ?></td>
                            <td><?= htmlspecialchars(substr($run['notes'], 0, 50)) . (strlen($run['notes']) > 50 ? '...' : '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </figure>

        <?php endif; ?>
        
        <br>
        <button><a href="./index.php" role="button" class="secondary">Retour au Dashboard</a></button>
        <button><a href="./create.php" role="button" class="secondary">Ajouter une Course</a></button>

    </main>

    <?php include __DIR__ . '/../../src/i18n/language-footer.php'; ?>
</body>

</html>