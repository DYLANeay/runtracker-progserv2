<?php
/**
 * Registration Page
 *
 * Handles new user registration with validation and account creation.
 * Creates user account in database, sends welcome email, and establishes session.
 *
 * @uses $_SESSION['user_id'] Set on successful registration
 * @uses $_SESSION['username'] Set on successful registration
 * @uses $_POST['username'] Username from registration form
 * @uses $_POST['email'] Email address from registration form
 * @uses $_POST['password'] Password from registration form
 * @uses $_POST['password_confirm'] Password confirmation from registration form
 *
 * Security: Password hashing using password_hash(), email validation, input sanitization
 * Access: Public (unauthenticated users)
 */

session_start();

require __DIR__ . "/../src/utils/autoloader.php";
require __DIR__ . "/../src/i18n/Language.php";
require __DIR__ . '/../src/utils/send_email_welcome.php'; 
 __DIR__ . '/../config/database.ini';

use RunTracker\Database\Database;
use RunTracker\I18n\Language;
use function RunTracker\I18n\t;
use function RunTracker\I18n\currentLang;

/** @var Language $lang Language instance for translations */
$lang = Language::getInstance();

/** @var string $message Success or error message to display */
$message = "";

/** @var string $messageType Type of message ('error' or 'success') for styling */
$messageType = "";

/**
 * Check if user is already logged in (currently disabled)
 */
if (isset($_SESSION["user_id"])) {
    // header('Location: index.php');
    // exit();
}

/**
 * Process registration form submission
 * Validates input, creates user account, and sends welcome email
 */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    /** @var string $username Sanitized username from form */
    $username = htmlspecialchars(trim($_POST["username"]));

    /** @var string $email Sanitized email address from form */
    $email = htmlspecialchars(trim($_POST["email"]));

    /** @var string $password Raw password from form */
    $password = $_POST["password"];

    /** @var string $passwordConfirm Password confirmation from form */
    $passwordConfirm = $_POST["password_confirm"];

    if (
        empty($username) ||
        empty($email) ||
        empty($password) ||
        empty($passwordConfirm)
    ) {
        $message = t("register_error_empty");
        $messageType = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = t("register_error_email");
        $messageType = "error";
    } elseif ($password !== $passwordConfirm) {
        $message = t("register_error_password_mismatch");
        $messageType = "error";
    } else {
        try {
            $db = new Database();
            $pdo = $db->getPdo();

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare(
                "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)",
            );

            if (
                $stmt->execute([
                    "username" => $username,
                    "email" => $email,
                    "password" => $hashedPassword,
                ])
            ) {
                // sendWelcomeEmail($email, $username);

                $_SESSION["user_id"] = $pdo->lastInsertId();
                $_SESSION["username"] = $username;

                header("Location: index.php");
                exit();
            } else {
                $message = t("register_error_exists");
                $messageType = "error";
            }
        } catch (Exception $e) {
            $message = t("register_error_system");
            $messageType = "error";
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

    <title><?= t("register_title") ?> | <?= t("app_name") ?></title>
</head>

<body>
    <main class="container">
        <hgroup>
            <h1><?= t("register_title") ?></h1>
            <h3><?= t("register_subtitle") ?></h3>
        </hgroup>

        <?php if (!empty($message)): ?>
            <article class="<?= $messageType === "error"
                ? "error"
                : "success" ?>"
                    style="background-color: <?= $messageType === "error"
                        ? "#f8d7da"
                        : "#d4edda" ?>;
                           color: <?= $messageType === "error"
                               ? "#721c24"
                               : "#155724" ?>;
                           padding: 1rem; border-radius: 5px;">
                <?= $message ?>
            </article>
        <?php endif; ?>

        <form method="post" action="">
            <label for="username"><?= t("register_username") ?></label>
            <input type="text" id="username" name="username" required
                   value="<?= isset($username)
                       ? htmlspecialchars($username)
                       : "" ?>">

            <label for="email"><?= t("register_email") ?></label>
            <input type="email" id="email" name="email" required
                   value="<?= isset($email) ? htmlspecialchars($email) : "" ?>">

            <label for="password"><?= t("register_password") ?></label>
            <input type="password" id="password" name="password" required>

            <label for="password_confirm"><?= t(
                "register_password_confirm",
            ) ?></label>
            <input type="password" id="password_confirm" name="password_confirm" required>

            <button type="submit"><?= t("register_submit") ?></button>
        </form>

        <p><small><?= t("register_has_account") ?> <a href="login.php"><?= t(
     "register_login_here",
 ) ?></a></small></p>

        <br>
        <button><a href="./index.php" role="button" class="secondary"><?= t("back_to_home") ?></a></button>
    </main>

    <?php include __DIR__ . "/../src/i18n/language-footer.php"; ?>
</body>

</html>
