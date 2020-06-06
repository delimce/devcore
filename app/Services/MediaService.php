<?php


namespace App\Services;

use App\Models\Media\GarageMedia;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MediaService
{

    const TEMP_MEDIA_FOLDER = "garage";

    /**
     * save file to temp folder /storage/app/media/temp
     * @param mixed $file
     * @param array $meta
     */
    public function saveToTempFolder($file, $meta)
    {

        $garageFolder = static::TEMP_MEDIA_FOLDER . $meta["garage_id"];
        $storagePath = Storage::disk('media')->put($garageFolder, $file);
        GarageMedia::create([
            'garage_id' => $meta["garage_id"],
            'original' => $meta["original"],
            'extension' => $meta["extension"],
            'path' => $storagePath,
            'size' => $meta["size"],
            'mime' => $meta["mime"],
        ]);
    }


    /**
     * @param int $garageId
     * @param array $files
     */
    public function getGarageFilesFromMedia(int $garageId)
    {
        $mediaFiles = GarageMedia::whereGarageId($garageId)->get();
        $data = $mediaFiles->map(function ($item, $key) {
             return [
                "path" => $item->path,
                "name" => $item->original,
                "size" => $item->size,
                "mime" => $item->mime,
                "url" => Storage::disk('media')->url($item->path)
            ];
        });
        return $data;
    }


    /**
     * remove media files
     * @param array $data
     */
    public function removeGarageMediaFile($data)
    {
        DB::transaction(function () use ($data) {
            $file = GarageMedia::wheregarageId($data['garage_id'])->where('original', $data['path'])->first();
            Storage::disk('media')->delete($file->path);
            $file->delete();
        });
    }


    /**
     * get media file
     * @param String $path
     * @param mixed|bool $file
     */
    public function getMediaFile($path)
    {
        if(file_exists(Storage::disk('media')->path($path)))
        {
            $file["path"] = Storage::disk('media')->get($path);
            $file["type"] = Storage::disk('media')->mimeType($path);
            return $file;
        }
        return false;
    }



    /**
     * converts bytes to Mb
     * @param int $B
     */
    public static function toMb(int $B)
    {
        return round(($B / 1024) / 1024, 2);
    }

    /**
     * converts bytes to Kb
     * @param int $B
     */
    public static function toKb(int $B)
    {
        return round(($B / 1024), 2);
    }
}
