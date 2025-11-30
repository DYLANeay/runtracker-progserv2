<?php
/**
 * Language Footer Component
 *
 * Displays language switcher links in the footer.
 * Allows users to switch between available languages.
 */

use RunTracker\I18n\Language;

$lang = Language::getInstance();
$currentLang = $lang->getCurrentLang();
$currentUrl = $_SERVER['PHP_SELF'];
$queryString = $_GET;
?>

<footer style="text-align: center; padding: 2rem 0; margin-top: 3rem; border-top: 1px solid #ccc;">
    <small>
        <?php foreach ($lang->getAvailableLanguages() as $langCode): ?>
            <?php
            $queryString['lang'] = $langCode;
            $url = $currentUrl . '?' . http_build_query($queryString);
            ?>
            <?php if ($currentLang === $langCode): ?>
                <strong><?= strtoupper($langCode) ?></strong>
            <?php else: ?>
                <a href="<?= $url ?>"><?= strtoupper($langCode) ?></a>
            <?php endif; ?>
            <?php if ($langCode !== end($lang->getAvailableLanguages())): ?>
                <span> | </span>
            <?php endif; ?>
        <?php endforeach; ?>
    </small>
</footer>