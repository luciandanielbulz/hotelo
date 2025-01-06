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
}
