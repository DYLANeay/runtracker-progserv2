<?php
/**
 * Logout Page
 *
 * Terminates user session and redirects to login page.
 * Clears all session data and destroys the session.
 *
 * @uses $_SESSION Cleared and destroyed
 *
 * Security: Properly destroys session to prevent session hijacking
 * Access: Public (no authentication check required)
 */

session_start();

/**
 * Clear all session variables
 */
$_SESSION = [];

/**
 * Destroy the session
 */
session_destroy();

/**
 * Redirect to login page
 */
header("Location: login.php");
exit();
?>
