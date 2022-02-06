<?php

namespace App\Http\Controllers\Web;

use App\Services\Commons\MediaFileService;
use Laravel\Lumen\Routing\Controller as BaseController;

class MediaController extends BaseController
{
    public function __construct(private MediaFileService $media)
    {
    }

    public function serve($folder, $file)
    {
        if (empty($folder) || empty($file)) {
            return redirect('errors.404');
        }

        $path = $folder . "/" . $file;
        $file = $this->media->getMediaFile($path);
        if ($file) {
            return  response($file["path"], 200)->header('Content-Type', $file["type"]);
        }
        return redirect('errors.404');
    }
}
