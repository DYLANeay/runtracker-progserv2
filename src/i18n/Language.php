<?php

class Language {
    private static $instance = null;
    private $translations = [];
    private $currentLang = 'fr';
    private $availableLanguages = ['fr', 'en', 'de'];

    private function __construct() {
        $this->initLanguage();
        $this->loadTranslations();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Language();
        }
        return self::$instance;
    }

    private function initLanguage() {

        if (isset($_GET['lang']) && in_array($_GET['lang'], $this->availableLanguages)) {
            $this->currentLang = $_GET['lang'];
            setcookie('lang', $this->currentLang, time() + (365 * 24 * 60 * 60), '/'); // 1 year
        } elseif (isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], $this->availableLanguages)) {
            $this->currentLang = $_COOKIE['lang'];
        }
        //Fallback to browser settings (default one)
        elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            if (in_array($browserLang, $this->availableLanguages)) {
                $this->currentLang = $browserLang;
            }
        }
    }

    private function loadTranslations() {
        $langFile = __DIR__ . "/translations/{$this->currentLang}.php";

        if (file_exists($langFile)) {
            $this->translations = require $langFile;
        } else {
            // Fallback to French
            $this->translations = require __DIR__ . "/translations/fr.php";
        }
    }

    public function get($key) {
        return $this->translations[$key] ?? $key;
    }

    public function getCurrentLang() {
        return $this->currentLang;
    }

    public function getAvailableLanguages() {
        return $this->availableLanguages;
    }
}

function t($key) {
    return Language::getInstance()->get($key);
}

function currentLang() {
    return Language::getInstance()->getCurrentLang();
}
