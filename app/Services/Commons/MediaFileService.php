<?php

namespace App\Services\Commons;

use App\Repositories\MediaRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MediaFileService
{
    /** @var MediaRepository $mediaRepository */
    private $mediaRepository;

    const TEMP_MEDIA_FOLDER = "garage";

    public function __construct(MediaRepository $mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;
    }

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
            $this->mediaRepository->create([
                'garage_id' => $meta["garage_id"],
                'original' => $meta["original"],
                'extension' => $meta["extension"],
                'path' => $storagePath,
                'size' => $meta["size"],
                'mime' => $meta["mime"],
            ]);
        } catch (\Exception $ex) {
            Log::error($ex);
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
        $mediaFiles = $this->mediaRepository->getByGarageId($garageId);
        return $mediaFiles->map(function ($item) {
            return [
                "path" => $item->path,
                "name" => $item->original,
                "size" => $item->size,
                "mime" => $item->mime,
                "url" => Storage::disk('media')->url($item->path)
            ];
        });
    }

    /**
     * removeGarageMediaFile
     *
     * @param  array $data
     * @return bool
     */
    public function removeGarageMediaFile($data): bool
    {
        $data = ["garage_id" => $data['garage_id'], "original" => $data['path']];
        $file = $this->mediaRepository->getFirstByFilters($data);

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
        $result = $this->mediaRepository->getFirstByFilters(["garage_id" => $garageId]);
        return !is_null($result) ? $result : false;
    }


    /**
     * converts bytes to Mb
     * @param int $bites
     */
    public static function toMb(int $bites)
    {
        return round(($bites / 1024) / 1024, 2);
    }

    /**
     * converts bytes to Kb
     * @param int $bites
     */
    public static function toKb(int $bites)
    {
        return round(($bites / 1024), 2);
    }
}
