<?php
/**
 * Database Interface
 *
 * Defines the contract for database connection classes.
 * Ensures all database implementations provide PDO access.
 *
 * Security: Forces type-safe PDO implementation
 */

namespace RunTracker\Database;

use PDO;

interface DatabaseInterface
{
    public function getPdo(): PDO;
}
