<?php
/**
 * Create Run Page
 *
 * Allows authenticated users to record new running activities.
 * Validates input data, calculates pace automatically, and stores run in database.
 *
 * @uses $_SESSION['user_id'] User ID for associating run with user
 * @uses $_SESSION['username'] Username for display
 * @uses $_POST['date'] Run date from form
 * @uses $_POST['distance'] Distance in kilometers
 * @uses $_POST['duration'] Duration in HH:MM:SS format
 * @uses $_POST['notes'] Optional notes about the run
 *
 * Security: Requires authenticated user session, input validation and sanitization
 * Access: Authenticated users only
 */

session_start();

/**
 * Check if user is authenticated
 * Redirects to login page if no valid session exists
 */
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require __DIR__ . "/../src/utils/autoloader.php";
require __DIR__ . "/../src/i18n/Language.php";

use RunTracker\Database\Database;
use RunTracker\I18n\Language;
use function RunTracker\I18n\t;
use function RunTracker\I18n\currentLang;

/** @var Language $lang Language instance for translations */
$lang = Language::getInstance();

/** @var string $username Current logged-in user's username */
$username = $_SESSION["username"] ?? "Utilisateur";

/** @var int $user_id Current logged-in user's ID */
$user_id = $_SESSION["user_id"];

/** @var string $message Success message to display */
$message = "";

/** @var string $messageType Type of message for styling */
$messageType = "";

/** @var array $errors Array of validation error messages */
$errors = [];

/** @var string $date Run date from form */
$date = $distance = $duration_str = $notes = "";

/** @var string $pace Calculated pace in HH:MM:SS format per kilometer */
$pace = "00:00:00";

/**
 * Process run creation form submission
 * Validates input, calculates pace, and saves run to database
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /** @var int $pace_seconds Calculated pace in seconds per kilometer */
    $pace_seconds = 0;

    /** @var string $date Sanitized run date */
    $date = htmlspecialchars(trim($_POST["date"] ?? ""));

    /** @var float $distance Run distance in kilometers */
    $distance = floatval($_POST["distance"] ?? 0);

    /** @var string $duration_str Sanitized duration string in HH:MM:SS format */
    $duration_str = htmlspecialchars(trim($_POST["duration"] ?? ""));

    /** @var string $notes Sanitized run notes */
    $notes = htmlspecialchars(trim($_POST["notes"] ?? ""));

    if (empty($date)) {
        $errors[] = t("create_error_date");
    }
    if ($distance <= 0) {
        $errors[] = t("create_error_distance");
    }
    if (empty($duration_str)) {
        $errors[] = t("create_error_duration");
    }

    if (
        !preg_match('/^(\d{2}):(\d{2}):(\d{2})$/', $duration_str) &&
        !empty($duration_str)
    ) {
        $errors[] = t("create_error_duration_format");
    }

    /**
     * Save run to database if validation passes
     */
    if (empty($errors)) {
        try {
            $db = new Database();
            $pdo = $db->getPdo();

            /**
             * Parse duration string and convert to total seconds
             */
            [$hours, $minutes, $seconds] = sscanf($duration_str, "%d:%d:%d");
            $total_seconds = $hours * 3600 + $minutes * 60 + $seconds;

            /**
             * Calculate pace in seconds per kilometer
             */
            $pace_seconds = round($total_seconds / $distance);

            /**
             * Format pace as HH:MM:SS
             */
         

$seconds_int = (int) round($pace_seconds);


$pace = sprintf(
    "%02d:%02d:%02d",
    intdiv($seconds_int, 3600),       
    intdiv($seconds_int, 60) % 60,    
    $seconds_int % 60                 
);
               

           

            $stmt = $pdo->prepare('
                INSERT INTO runs (user_id, date, distance, duration, pace, notes)
                VALUES (:user_id, :date, :distance, :duration, :pace, :notes)
            ');

            $stmt->execute([
                "user_id" => $user_id,
                "date" => $date,
                "distance" => $distance,
                "duration" => $duration_str,
                "pace" => $pace,
                "notes" => $notes,
            ]);

            $message = sprintf(t("create_success"), $pace);
            $messageType = "success";

            $date = $distance = $duration_str = $notes = "";
        } catch (Exception $e) {
            $errors[] = t("create_error_save");
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

    <title><?= t("create_title") ?> | <?= t("app_name") ?></title>
</head>

<body>
    <header class="container">
        <nav>
            <ul>
                <li><strong><?= t("app_name") ?></strong></li>
            </ul>
            <ul>
                <li><?= t("welcome_user") ?> <strong><?= htmlspecialchars(
     $username,
 ) ?></strong> !</li>
                <li><a href="./logout.php" role="button" class="secondary"><?= t(
                    "logout",
                ) ?></a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <h1><?= t("create_title") ?></h1>

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
                <legend><?= t("create_welcome") ?></legend>

                <label for="date"><?= t("create_date") ?></label>
                <input type="date" id="date" name="date" required value="<?= htmlspecialchars(
                    $date,
                ) ?>">

                <label for="distance"><?= t("create_distance") ?></label>
                <input type="number" step="0.01" min="0.1" id="distance" name="distance" required value="<?= htmlspecialchars(
                    $distance,
                ) ?>">

                <label for="duration"><?= t("create_duration") ?></label>
                <input type="text" id="duration" name="duration" pattern="(\d{2}):(\d{2}):(\d{2})" placeholder="<?= t(
                    "create_duration_placeholder",
                ) ?>" required value="<?= htmlspecialchars($duration_str) ?>">

                <label for="notes"><?= t("create_notes") ?></label>
                <textarea id="notes" name="notes" rows="4" cols="50"><?= htmlspecialchars(
                    $notes,
                ) ?></textarea>

                <br>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit"><?= t("create_submit") ?></button>
                    <button type="reset" class="secondary"><?= t(
                        "create_reset",
                    ) ?></button>
                </div>
            </fieldset>
        </form>

        <br>

        <button><a href="./index.php" role="button" class="secondary"><?= t(
            "create_view_dashboard",
        ) ?></a></button>
        <button><a href="./progress.php" role="button" class="secondary"><?= t(
            "create_view_progress",
        ) ?></a></button>

    </main>

    <?php include __DIR__ . "/../src/i18n/language-footer.php"; ?>
</body>

</html>
