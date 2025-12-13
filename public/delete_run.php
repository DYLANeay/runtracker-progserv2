<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require __DIR__ . "/../src/utils/autoloader.php";
use RunTracker\Database\Database;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['run_id'])) {
    
    $run_id = (int) $_POST['run_id'];
    $user_id = $_SESSION["user_id"];

    try {
        $db = new Database();
        $pdo = $db->getPdo();

    
        $stmt = $pdo->prepare("DELETE FROM runs WHERE id = :id AND user_id = :user_id");
        
        $stmt->execute([
            'id' => $run_id,
            'user_id' => $user_id
        ]);

    } catch (Exception $e) {
   
    }
}


header("Location: progress.php");
exit();