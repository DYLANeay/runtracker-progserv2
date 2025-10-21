<?php
// Charge les classes automatiquement
spl_autoload_register(function ($className) {
    // Convertit les séparateurs de namespace en séparateurs de répertoires
    $classPath = str_replace('\\', '/', $className);

    // Construit le chemin complet du fichier
    $filePath = __DIR__ . '/../classes/' . $classPath . '.php';

    // Vérifie si le fichier existe avant de l'inclure
    if (file_exists($filePath)) {
        // Inclut le fichier de classe
        require_once $filePath;
    }
});
