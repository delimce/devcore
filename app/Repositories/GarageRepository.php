<?php

namespace App\Repositories;

use App\Models\Manager\Garage;
use App\Models\Manager\Network;
use App\Models\Manager\Schedule;
use App\Models\Manager\Segment;
use App\Models\Manager\ServiceCategory;
use App\Models\Manager\ServiceType;
use App\Services\StringsHandlerService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon as Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class GarageRepository
{
    const SCHEDULE_ERROR = 'errors.validate.schedule.day';

    /**
     * getById
     *
     * @param  int $garageId
     * @return Model
     */
    public function getById(int $garageId): Model
    {
        return Garage::find($garageId);
    }


    /**
     * getByUrl
     *
     * @param string $url
     * @return mixed
     */
    public function getByUrl(string $url)
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
            "services.service:id,type,name",
            "media:garage_id,mime,path"
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
     * save garage information
     * @param array $garage
     * @return int|bool
     */
    public function saveGarage(array $garage): bool
    {
        try {
            $result = Garage::firstOrNew(
                [
                    'manager_id' => $garage['manager'],
                ]
            );
            $result->name = $garage['name'];
            $result->url = StringsHandlerService::slugify($garage['name']);
            $result->phone = $garage['phone'];
            $result->address = $garage['address'];
            $result->network_id = (!empty($garage['network_id'])) ? $garage['network_id'] : null;
            $result->address2 = (isset($garage['address2'])) ? $garage['address2'] : "";
            $result->desc = (isset($garage['desc'])) ? $garage['desc'] : "";
            $result->country_id = $garage['country_id'];
            $result->state_id = $garage['state_id'];
            $result->province_id = $garage['province_id'];
            $result->zipcode = $garage['zipcode'];
            $result->enable = 1; # @todo: temporaly to make garage be able to search
            $result->save();
            return $result->id;
        } catch (QueryException $ex) {
            Log::error($ex);
            return false;
        }
    }


    /**
     * retrieves schedule
     * @param int $garageId
     * @return string[]
     */
    public function getScheduleById($garageId): array
    {
        $response = [];
        $garageSchedule = Schedule::whereGarageId($garageId)->orderBy("day")->get();
        $response["garage"] = $garageId;
        $response["schedule"] = $garageSchedule->toArray();
        return $response;
    }


    /**
     * check garage schedule to saving
     * @param array $schedule
     * @return array
     */
    public function garageScheduleValidation($schedule): array
    {
        $result = ["ok" => true, "message" => ""];
        // 7 days of week
        if (count($schedule) < 7) {
            $result["message"] = __('errors.validate.schedule.ndays');
            $result["ok"] = false;
            return $result;
        }

        array_walk($schedule, function ($day, $index) use (&$result) {
            $m1 = Carbon::parse($day["am1"]);
            $m2 = Carbon::parse($day["am2"]);
            $n1 = Carbon::parse($day["pm1"]);
            $n2 = Carbon::parse($day["pm2"]);

            //only morning
            if (
                (!is_null($day["am1"]) && !is_null($day["am2"]) && is_null($day["pm1"]) && is_null($day["pm2"])) &&
                ($m1->greaterThan($m2))
            ) {
                $result["message"] = __(static::SCHEDULE_ERROR, ["day" => $index + 1]);
                $result["ok"] = false;
                return $result;
            }


            //only afternoon
            if (
                (is_null($day["am1"]) && is_null($day["am2"]) && !is_null($day["pm1"]) && !is_null($day["pm2"])) &&
                ($n1->greaterThan($n2))
            ) {
                $result["message"] = __(static::SCHEDULE_ERROR, ["day" => $index + 1]);
                $result["ok"] = false;
                return $result;
            }

            //all day
            if (
                (!is_null($day["am1"]) && is_null($day["am2"]) && is_null($day["pm1"]) && !is_null($day["pm2"])) &&
                ($m1->greaterThan($n2))
            ) {
                $result["message"] = __(static::SCHEDULE_ERROR, ["day" => $index + 1]);
                $result["ok"] = false;
                return $result;
            }

            // all times filled
            if (
                (!is_null($day["am1"]) && !is_null($day["am2"]) && !is_null($day["pm1"]) && !is_null($day["pm2"])) &&
                ($m1->greaterThan($m2) || $m2->greaterThan($n1) || $n1->greaterThan($n2))
            ) {
                $result["message"] = __(static::SCHEDULE_ERROR, ["day" => $index + 1]);
                $result["ok"] = false;
                return $result;
            }
        });

        return $result;
    }

    /**
     * saving schedule
     * @param int $garageId
     * @param array $schedule
     */
    public function saveGarageSchedule($garageId, $schedule)
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
     * retrieve car segments
     * @param array
     */
    public function getCarSegments()
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
                "services:garage_id,segment,type",
                "media:garage_id,mime,path"
            ])
            ->get(["garage.*"]);
    }
}
