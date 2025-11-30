<?php
/**
 * Language Class
 *
 * Handles internationalization (i18n) for the application.
 * Implements singleton pattern for translation management.
 * Supports language switching via URL parameters, cookies, or browser settings.
 *
 * Supported languages: French (fr), English (en), German (de)
 *
 * @uses $_GET['lang'] Language parameter from URL
 * @uses $_COOKIE['lang'] Stored language preference
 * @uses $_SERVER['HTTP_ACCEPT_LANGUAGE'] Browser language preference
 *
 * Security: Language selection validated against whitelist
 */

namespace RunTracker\I18n;

class Language {
    /** @var Language|null $instance Singleton instance */
    private static $instance = null;

    /** @var array $translations Loaded translations for current language */
    private $translations = [];

    /** @var string $currentLang Current active language code */
    private $currentLang = 'fr';

    /** @var array $availableLanguages Whitelist of supported languages */
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

/**
 * Translation helper function
 *
 * Retrieves translated string for given key in current language.
 *
 * @param string $key Translation key
 * @return string Translated string or key if not found
 */
function t($key) {
    return Language::getInstance()->get($key);
}

/**
 * Current language helper function
 *
 * Returns the currently active language code.
 *
 * @return string Current language code (fr, en, de)
 */
function currentLang() {
    return Language::getInstance()->getCurrentLang();
}
