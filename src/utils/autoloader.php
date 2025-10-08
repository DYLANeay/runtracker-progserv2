<?php
// Charge les classes automatiquement
spl_autoload_register(function () {
    // Convertit les séparateurs de namespace en séparateurs de répertoires
     = str_replace('\', '/', );

    // Construit le chemin complet du fichier
     = __DIR__ . '/../classes/' .  . '.php';

    // Vérifie si le fichier existe avant de l'inclure
    if (file_exists()) {
        // Inclut le fichier de classe
        require_once ;
    }
});
