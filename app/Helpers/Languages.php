<?php

namespace App\Helpers;

class Languages
{
    /**
     * Get list of languages spoken in the Philippines
     */
    public static function getLanguages(): array
    {
        return [
            'English',
            'Filipino',
            'Tagalog',
            'Cebuano',
            'Ilocano',
            'Hiligaynon',
            'Waray',
            'Kapampangan',
            'Pangasinan',
            'Bikol',
            'Maranao',
            'Maguindanao',
            'Tausug',
            'Chavacano',
        ];
    }

    /**
     * Get default languages (English and Filipino)
     */
    public static function getDefault(): array
    {
        return ['English', 'Filipino'];
    }

    /**
     * Get available languages (alias for getLanguages)
     */
    public static function available(): array
    {
        return self::getLanguages();
    }
}
