<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Str;

class StringsHandlerService{

    
    /**
     * slugify
     *
     * @param  string $content
     * @return string
     */
    static public function slugify(string $content)
    {
        return Str::slug($content, '-');
    }


}