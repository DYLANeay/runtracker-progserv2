<?php
/**
 * Login Page
 *
 * Handles user authentication through username and password validation.
 * Verifies credentials against the database and creates user session on success.
 *
 * @uses $_SESSION['user_id'] Set on successful login
 * @uses $_SESSION['username'] Set on successful login
 * @uses $_POST['username'] Username from login form
 * @uses $_POST['password'] Password from login form
 *
 * Security: Password verification using password_verify()
 * Access: Public (unauthenticated users only, redirects if already logged in)
 */

session_start();

require __DIR__ . "/../src/utils/autoloader.php";
require __DIR__ . "/../src/i18n/Language.php";

use RunTracker\Database\Database;
use RunTracker\I18n\Language;
use function RunTracker\I18n\t;
use function RunTracker\I18n\currentLang;

/** @var Language $lang Language instance for translations */
$lang = Language::getInstance();

/** @var string $erreur Error message to display to user */
$erreur = "";

/**
 * Redirect to dashboard if user is already logged in
 */
if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

/**
 * Process login form submission
 * Validates credentials and creates user session
 */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    /** @var string $username Sanitized username from form */
    $username = htmlspecialchars(trim($_POST["username"]));

    /** @var string $password Raw password from form (not sanitized for verification) */
    $password = $_POST["password"];

    if (empty($username) || empty($password)) {
        $erreur = t("login_error_empty");
    } else {
        try {
            $db = new Database();
            $pdo = $db->getPdo();

            $stmt = $pdo->prepare(
                "SELECT id, username, password FROM users WHERE username = :username",
            );
            $stmt->execute(["username" => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user["password"])) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];

                header("Location: index.php");
                exit();
            } else {
                $erreur = t("login_error_invalid");
            }
        } catch (Exception $e) {
            $erreur = t("login_error_invalid");
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

    <title><?= t("login_title") ?> | <?= t("app_name") ?></title>
</head>

<body>
    <main class="container">
        <hgroup>
            <h1><?= t("login_title") ?></h1>
            <h3><?= t("login_subtitle") ?></h3>
        </hgroup>

        <?php if (!empty($erreur)): ?>
            <article class="error" style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 5px;">
                <?= $erreur ?>
            </article>
        <?php endif; ?>

        <form method="post" action="">
            <label for="username"><?= t("login_username") ?></label>
            <input type="text" id="username" name="username" required>

            <label for="password"><?= t("login_password") ?></label>
            <input type="password" id="password" name="password" required>

            <button type="submit"><?= t("login_submit") ?></button>
        </form>

        <p><small><?= t("login_no_account") ?> <a href="register.php"><?= t(
     "login_register_here",
 ) ?></a></small></p>

        <br>
        <!-- <button><a href="index.php"><?= t("back_to_home") ?></a></button> -->
    </main>

    <?php include __DIR__ . "/../src/i18n/language-footer.php"; ?>
</body>

</html>
