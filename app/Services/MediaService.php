<?php


namespace App\Services;

use App\Models\Media\GarageMedia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MediaService
{

    const TEMP_MEDIA_FOLDER = "garage";


    /**
     * saveGarageMedia
     * save file to temp folder /storage/app/media/temp
     *
     * @param  mixed $file
     * @param  mixed $meta
     * @return bool
     */
    public function saveGarageMedia($file, $meta)
    {
        $result = true;
        try {
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
        } catch (\Exception $ex) {
            Log::error("file couldn't be uploaded", $ex->getMessage());
            $result = false;
        } finally {
            return $result;
        }
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
     * removeGarageMediaFile
     *
     * @param  mixed $data
     * @return bool
     */
    public function removeGarageMediaFile($data)
    {
        $file = GarageMedia::wheregarageId($data['garage_id'])->where('original', $data['path'])->first();
        if (!is_null($file)) {
            Storage::disk('media')->delete($file->path);
            $file->delete();
            return true;
        }
        return false;
    }


    /**
     * get media file
     * @param String $path
     * @param mixed|bool $file
     */
    public function getMediaFile($path)
    {
        if (file_exists(Storage::disk('media')->path($path))) {
            $file["path"] = Storage::disk('media')->get($path);
            $file["type"] = Storage::disk('media')->mimeType($path);
            return $file;
        }
        return false;
    }

    /**
     * @param int $garageId
     */
    public function getFirstMediaFileByGarageId($garageId)
    {

        $result = GarageMedia::whereGarageId($garageId)->first();
        return !is_null($result) ? $result : false;
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
