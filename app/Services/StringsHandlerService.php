<?php

namespace App\Services;

use Illuminate\Support\Str;

class StringsHandlerService{

    const TERM_LANG_PREF =  "terms.";
    const EMPTY_STRING = '';


    /**
     * slugify
     *
     * @param  string $content
     * @return string
     */
    static public function slugify(string $content): string
    {
        return Str::slug($content, '-');
    }

    /**
     * @param $term
     * @return array|string|null
     */
    public static function getTermTranslated($term)
    {
        $find = static::TERM_LANG_PREF . $term;
        return ($term) ? __($find) : static::EMPTY_STRING;
    }


}
