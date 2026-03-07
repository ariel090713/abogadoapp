<?php

namespace App\Helpers;

class TextHelper
{
    /**
     * Convert URLs in text to clickable links
     * 
     * @param string|null $text
     * @return string
     */
    public static function linkify(?string $text): string
    {
        if (empty($text)) {
            return '';
        }

        // URL pattern
        $pattern = '/(https?:\/\/[^\s]+)/i';
        
        // Replace URLs with anchor tags
        $text = preg_replace_callback($pattern, function($matches) {
            $url = htmlspecialchars($matches[0], ENT_QUOTES, 'UTF-8');
            return '<a href="' . $url . '" target="_blank" rel="noopener noreferrer" class="underline hover:opacity-80">' . $url . '</a>';
        }, $text);
        
        return $text;
    }
}
