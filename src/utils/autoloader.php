<?php
/**
 * PSR-4 Autoloader
 *
 * Automatically loads classes based on namespace and class name.
 * Supports the RunTracker namespace structure.
 *
 * Namespace mapping:
 * - RunTracker\Database → src/classes/Database/
 * - RunTracker\I18n → src/i18n/
 * - RunTracker\Utils → src/utils/
 *
 * @uses spl_autoload_register() PHP's standard autoload mechanism
 */

spl_autoload_register(function ($className) {
    /** @var string $prefix Base namespace for the application */
    $prefix = 'RunTracker\\';

    /** @var int $len Length of the base namespace */
    $len = strlen($prefix);

    /**
     * Check if the class uses the RunTracker namespace
     */
    if (strncmp($prefix, $className, $len) !== 0) {
        return;
    }

    /**
     * Get the relative class name (without base namespace)
     */
    $relativeClass = substr($className, $len);

    /**
     * Map namespaces to directories
     */
    $namespaceMappings = [
        'Database\\' => __DIR__ . '/../classes/Database/',
        'I18n\\' => __DIR__ . '/../i18n/',
        'Utils\\' => __DIR__ . '/../utils/',
    ];

    /**
     * Try each namespace mapping
     */
    foreach ($namespaceMappings as $namespace => $directory) {
        if (strncmp($namespace, $relativeClass, strlen($namespace)) === 0) {
            /** @var string $className Remove namespace prefix */
            $className = substr($relativeClass, strlen($namespace));

            /** @var string $file Full file path */
            $file = $directory . str_replace('\\', '/', $className) . '.php';

            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});
