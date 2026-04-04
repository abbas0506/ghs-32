<?php

namespace App\Helpers;

use ArPHP\I18N\Arabic;

class UrduHelper
{
    protected static ?Arabic $arabic = null;

    protected static function arabic(): Arabic
    {
        if (!static::$arabic) {
            static::$arabic = new Arabic();
        }
        return static::$arabic;
    }

    /**
     * Reshape and reorder a mixed Urdu+English string for DomPDF rendering.
     * English parts are left untouched; Urdu/Arabic parts are reshaped.
     */

    public static function reshape(?string $text): string
    {
        if (blank($text)) return '—';

        $arabic = static::arabic();

        // If no Urdu → return as-is
        if (!static::hasUrdu($text)) {
            return $text;
        }

        // 👇 Split Urdu and English parts
        $parts = preg_split('/([\x{0600}-\x{06FF}\s]+)/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE);

        foreach ($parts as &$part) {
            if (static::hasUrdu($part)) {
                // 👇 reshape only Urdu part
                $part = $arabic->utf8Glyphs($part);
            }
        }

        return implode('', $parts);
    }
    
    
    
    
    // public static function reshape(string|null $text): string
    // {
    //     if (blank($text)) return '—';

    //     $arabic = static::arabic();

    //     // Detect if string contains Arabic/Urdu Unicode block
    //     if (!static::hasUrdu($text)) {
    //         return $text; // pure English — no processing needed
    //     }

    //     // $arabic->setTextDirection('rtl');

    //     // Reshape: connects Arabic/Urdu letters properly
    //     $reshaped = $arabic->utf8Glyphs($text);

    //     return $reshaped;
    // }

    /**
     * Check if a string contains any Arabic/Urdu characters.
     */
    public static function hasUrdu(string $text): bool
    {
        // Arabic Unicode block: U+0600–U+06FF (covers Urdu too)
        return (bool) preg_match('/[\x{0600}-\x{06FF}]/u', $text);
    }
}
