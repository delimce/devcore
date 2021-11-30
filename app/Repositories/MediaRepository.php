<?php


namespace App\Repositories;

use App\Models\Media\GarageMedia;
use Illuminate\Database\Eloquent\Collection;

class MediaRepository
{
    /**
     * @param array $data
     * @return GarageMedia
     */
    public function create(array $data): GarageMedia
    {
        return GarageMedia::create($data);
    }

    /**
     * @param int $garageId
     * @return Collection
     */
    public function getByGarageId(int $garageId): Collection
    {
        return GarageMedia::whereGarageId($garageId)->get();
    }

    /**
     * @param array $filters
     * @return GarageMedia|null
     */
    public function getFirstByFilters(array $filters): ?GarageMedia
    {
        return GarageMedia::where($filters)->first();
    }

    /**
     * @param int $mediaId
     * @return void
     */
    public function deleteById(int $mediaId)
    {
        GarageMedia::find($mediaId)->delete();
    }
}
