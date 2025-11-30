<?php
/**
 * Database Class
 *
 * Handles MySQL database connection and table creation.
 * Implements singleton pattern through DatabaseInterface.
 * Auto-creates database and tables if they don't exist.
 *
 * @uses database.ini Configuration file for database credentials
 *
 * Security: Uses PDO with prepared statements, proper charset configuration
 */

namespace RunTracker\Database;

use PDO;
use Exception;

class Database implements DatabaseInterface {

    const DATABASE_CONFIGURATION_FILE = __DIR__ . '/../config/database.ini';

    /** @var PDO $pdo PDO database connection instance */
    private $pdo;

    public function __construct() {
        // Documentation : https://www.php.net/manual/fr/function.parse-ini-file.php
        $config = parse_ini_file(self::DATABASE_CONFIGURATION_FILE, true);

        if (!$config) {
            throw new Exception("Erreur lors de la lecture du fichier de configuration : " . self::DATABASE_CONFIGURATION_FILE);
        }

        $host = $config['host'];
        $port = $config['port'];
        $database = $config['database'];
        $username = $config['username'];
        $password = $config['password'];

        // Documentation :
        //   - https://www.php.net/manual/fr/pdo.connections.php
        //   - https://www.php.net/manual/fr/ref.pdo-mysql.connection.php
        $this->pdo = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $username, $password);

        // Création de la base de données si elle n'existe pas
        $sql = "CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        // Sélection de la base de données
        $sql = "USE `$database`;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        // Création de la table "users" si elle n'existe pas
        $sql = "CREATE TABLE IF NOT EXISTS users (
                id INT PRIMARY KEY AUTO_INCREMENT,
                username VARCHAR(50) UNIQUE NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_username (username),
                INDEX idx_email (email)
            ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();


        // Création de la table "runs" si elle n'existe pas
        $sql = "CREATE TABLE IF NOT EXISTS runs (
                id INT PRIMARY KEY AUTO_INCREMENT,
                user_id INT NOT NULL,
                date DATE NOT NULL,
                distance DECIMAL(5, 2) NOT NULL,
                duration TIME NOT NULL,
                pace TIME NOT NULL,
                notes TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE,
                INDEX idx_user_id (user_id),
                INDEX idx_date (date),
                INDEX idx_user_date (user_id, date)
            ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }

    public function getPdo(): PDO {
        return $this->pdo;
    }
}
