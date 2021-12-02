<?php

namespace App\Repositories;

use App\Models\Manager\Garage;
use App\Models\Manager\Network;
use App\Models\Manager\Schedule;
use App\Models\Manager\Segment;
use App\Models\Manager\ServiceCategory;
use App\Models\Manager\ServiceType;
use App\Models\Manager\Comment;
use App\Services\Commons\StringsHandlerService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class GarageRepository
{
    /**
     * getById
     *
     * @param  int $garageId
     * @return Garage
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById(int $garageId): Garage
    {
        return Garage::findOrFail($garageId);
    }

    /**
     * @param string $url
     * @return Garage|null
     */
    public function getByUrl(string $url): ?Garage
    {
        return Garage::where([
            "url" => $url,
            "enable" => 1
        ])->first();
    }


    /**
     * getDetailsById
     *
     * @param  int $garageId
     * @return Garage|Model
     */
    public function getDetailsById(int $garageId)
    {
        return Garage::with([
            "province:id,name",
            "state:id,name",
            "network:id,desc",
            "schedules",
            "services:id,garage_id,segment,category,price,service_id",
            "services.service:id,type,name,desc,order",
            "media:garage_id,mime,path",
            "comments:garage_id,user_id,comment",
            "comments.user:id,name,lastname"
        ])->find($garageId);
    }

    /**
     * @return mixed
     */
    public function getNetworks()
    {
        return Network::whereStatus(1)->get();
    }


    /**
     * @param integer $managerId
     * @return mixed
     */
    public function getGarageByManagerId($managerId)
    {
        return Garage::whereManagerId($managerId)->first();
    }


    /**
     * @param array $garage
     * @return Garage
     * @throws QueryException
     */
    public function create(array $garage): Garage
    {
        $result = Garage::firstOrNew(
            [
                'manager_id' => $garage['manager'],
            ]
        );
        $result->name        = $garage['name'];
        $result->url         = StringsHandlerService::slugify($garage['name']);
        $result->phone       = $garage['phone'];
        $result->address     = $garage['address'];
        $result->network_id  = (!empty($garage['network_id'])) ? $garage['network_id'] : null;
        $result->address2    = (isset($garage['address2'])) ? $garage['address2'] : "";
        $result->desc        = (isset($garage['desc'])) ? $garage['desc'] : "";
        $result->country_id  = $garage['country_id'];
        $result->state_id    = $garage['state_id'];
        $result->province_id = $garage['province_id'];
        $result->zipcode     = $garage['zipcode'];
        $result->enable      = 1; # @todo: temporaly to make garage be able to search
        $result->save();
        return $result;
    }


    /**
     * @param int $garageId
     * @return Collection
     */
    public function getGarageSchedule(int $garageId): Collection
    {
        return Schedule::whereGarageId($garageId)->orderBy("day")->get();
    }


    /**
     * @param int $garageId
     * @param array $schedule
     * @return void
     * @throws QueryException
     */
    public function saveSchedule(int $garageId, array $schedule)
    {
        DB::transaction(function () use ($garageId, $schedule) {
            Schedule::wheregarageId($garageId)->delete();
            array_walk($schedule, function ($day, $index) use ($garageId) {
                Schedule::create([
                    'garage_id' => $garageId,
                    'day' => $index,
                    'am1' => ($day["am1"]) ? $day["am1"] : null,
                    'am2' => ($day["am2"]) ? $day["am2"] : null,
                    'pm1' => ($day["pm1"]) ? $day["pm1"] : null,
                    'pm2' => ($day["pm2"]) ? $day["pm2"] : null,
                ]);
            });
        });
    }


    /**
     * @return Collection
     */
    public function getCarSegments(): Collection
    {
        return Segment::whereStatus(1)->get();
    }

    /**
     * getServiceTypes
     * @return Collection
     */
    public function getServiceTypes(): Collection
    {
        return ServiceType::all();
    }

    /**
     * getServiceCategories
     * @return Collection
     */
    public function getServiceCategories(): Collection
    {
        return ServiceCategory::all();
    }

    /**
     * @param int $garageId
     * @return Collection
     */
    public function getCommentsById(int $garageId): Collection
    {
        return Comment::whereGarageId($garageId)->get();
    }

    /**
     * search
     *
     * @param mixed[] $filters
     * @return Collection
     */
    public function search(array $filters): Collection
    {
        return Garage::where("enable", 1)->distinct()
            ->when($filters['city'], function ($q) use ($filters) {
                return $q->where('province_id', $filters['city']);
            })
            ->when($filters['zip'], function ($q) use ($filters) {
                return $q->where('zipcode', $filters['zip']);
            })
            ->when($filters['text'], function ($q) use ($filters) {
                $q->where(function ($query) use ($filters) {
                    return
                        $query->where('name', "LIKE", "%{$filters['text']}%")
                        ->orWhere('desc', "LIKE", "%{$filters['text']}%");
                });
            }) # advanced search
            ->when(($filters['type'] || $filters['segment'] || $filters['service']),
                function ($q) use ($filters) {
                    return $q->join('garage_service', 'garage.id', '=', 'garage_service.garage_id')
                        ->when($filters['type'], function ($q) use ($filters) {
                            return $q->where('type', $filters['type']);
                        })->when($filters['segment'], function ($q) use ($filters) {
                            return $q->where('segment', $filters['segment']);
                        })->when($filters['service'], function ($q) use ($filters) {
                            return $q->where('service_id', $filters['service']);
                        });
                }
            )
            ->with([
                "province:id,name:url",
                "state:id,name",
                "network:desc",
                "services:garage_id,segment,type",
                "media:garage_id,mime,path"
            ])
            ->get(["garage.*"]);
    }
}
