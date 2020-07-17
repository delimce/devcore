<?php

namespace App\Utils;

class Utilities
{

    const TERM_LANG_PREF =  "terms.";
    const EMPTY_STRING = '';

    public static function getTermTranslated($term)
    {
        $find = static::TERM_LANG_PREF . $term;
        return ($term) ? __($find) : static::EMPTY_STRING;
    }
}
