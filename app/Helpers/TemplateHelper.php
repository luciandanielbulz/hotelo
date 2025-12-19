<?php

namespace App\Helpers;

class TemplateHelper
{
    /**
     * Ersetzt Platzhalter in einem Template-String mit den gegebenen Werten.
     *
     * @param string $template Der Template-String mit Platzhaltern.
     * @param array $variables Ein assoziatives Array mit Platzhalter und deren Ersatzwerten.
     * @return string Der ersetzte String.
     */
    public static function replacePlaceholders(string $template, array $variables): string
    {
        // Extrahiere die Platzhalter
        $placeholders = array_keys($variables);
        $replacements = array_values($variables);

        // Ersetze die Platzhalter im Template
        return str_replace($placeholders, $replacements, $template);
    }

    /**
     * Gibt den Logo-Pfad basierend auf der aktuellen Domain zurück.
     *
     * @return string Der Asset-Pfad zum Logo
     */
    public static function getLogoPath(): string
    {
        $host = request()->getHost();
        $domainLogos = config('app.domain_logos', []);
        $defaultLogo = config('app.default_logo', 'quickBillLogo.png');
        
        $logoFilename = $domainLogos[$host] ?? $defaultLogo;
        
        return asset('logo/' . $logoFilename);
    }

    /**
     * Gibt den Favicon-Pfad basierend auf der aktuellen Domain zurück.
     *
     * @return string Der Asset-Pfad zum Favicon mit Cache-Busting
     */
    public static function getFaviconPath(): string
    {
        $host = request()->getHost();
        $domainFavicons = config('app.domain_favicons', []);
        $defaultFavicon = config('app.default_favicon', 'VenditioIcon.svg');
        
        $faviconFilename = $domainFavicons[$host] ?? $defaultFavicon;
        $faviconPath = public_path('logo/' . $faviconFilename);
        
        // Cache-Busting basierend auf Dateiänderungszeit
        $version = file_exists($faviconPath) ? filemtime($faviconPath) : time();
        
        return asset('logo/' . $faviconFilename) . '?v=' . $version;
    }
}
