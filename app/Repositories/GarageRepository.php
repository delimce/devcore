<?php

namespace App\Repositories;

use App\Models\Manager\Brand;
use App\Models\Manager\Garage;
use App\Models\Manager\GarageService as Gservice;
use App\Models\Manager\GarageService;
use App\Models\Manager\Network;
use App\Models\Manager\Schedule;
use App\Models\Manager\Segment;
use App\Models\Manager\Service;
use App\Models\Manager\ServiceCategory;
use App\Models\Manager\ServiceType;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon as Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class GarageRepository
{
    const TYPES = ["TYRE", "FILTER", "BATTERY", "CHECK", "OIL", "WORKFORCE", "BRAKE", "AC"];



    /**
     * getById
     *
     * @param  int $garageId
     * @return Collection
     */
    public function getById(int $garageId)
    {
        $garage = Garage::find($garageId);
        return $garage;
    }

    /**
     * @return mixed
     */
    public function getNetworks()
    {
        $networks = Network::whereStatus(1)->get();
        return $networks;
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
     * @return bool
     */
    public function saveGarage(array $garage)
    {
        try {
            $result = Garage::firstOrNew(
                [
                    'manager_id' => $garage['manager'],
                ]
            );
            $result->name = $garage['name'];
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
     * @return mixed
     */
    public function getScheduleById($garageId)
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
    public function garageScheduleValidation($schedule)
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
                $result["message"] = __('errors.validate.schedule.day', ["day" => $index + 1]);
                $result["ok"] = false;
                return $result;
            }


            //only afternoon
            if (
                (is_null($day["am1"]) && is_null($day["am2"]) && !is_null($day["pm1"]) && !is_null($day["pm2"])) &&
                ($n1->greaterThan($n2))
            ) {
                $result["message"] = __('errors.validate.schedule.day', ["day" => $index + 1]);
                $result["ok"] = false;
                return $result;
            }

            //all day
            if (
                (!is_null($day["am1"]) && is_null($day["am2"]) && is_null($day["pm1"]) && !is_null($day["pm2"])) &&
                ($m1->greaterThan($n2))
            ) {
                $result["message"] = __('errors.validate.schedule.day', ["day" => $index + 1]);
                $result["ok"] = false;
                return $result;
            }

            // all times filled
            if (
                (!is_null($day["am1"]) && !is_null($day["am2"]) && !is_null($day["pm1"]) && !is_null($day["pm2"])) &&
                ($m1->greaterThan($m2) || $m2->greaterThan($n1) || $n1->greaterThan($n2))
            ) {
                $result["message"] = __('errors.validate.schedule.day', ["day" => $index + 1]);
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
     * @return array
     */
    public function getServiceTypes()
    {
        return ServiceType::all();
    }


    /**
     * getServiceCategories
     * @return array
     */
    public function getServiceCategories()
    {
        return ServiceCategory::all();
    }


    /**
     * getServicesCatalogByQuery
     *
     * @param  string $segment
     * @param  string $type
     * @return Collection
     */
    public function getServicesCatalogByQuery($segment, $type)
    {
        $query = Service::query();
        if ($segment && !$type) {
            $query->where('segment', $segment)->orWhereNull('segment');
        } else {
            $query->where('type', $type)->where('status', 1)->where(function ($query) use ($segment) {
                $query->where('segment', $segment)->orWhereNull('segment');
            });
        }
        return $query->whereStatus(1)->get();
    }


    /**
     * getServiceBrandsByQuery
     * @param  string $type
     * @param  string $category
     * @return Collection
     */
    public function getServiceBrandsByQuery($type, $category)
    {
        $query = Brand::query();

        if ($type || $category) {
            if ($type && !$category) {
                $query->where('type', $type);
            } else {
                $query->where('type', $type)->where(function ($query) use ($category) {
                    $query->where('category', $category)->orWhereNull('category');
                });
            }
        }
        return $query->whereStatus(1)->get();
    }


    /**
     * getServiceList
     *
     * @param  int $garageId
     * @param  array $params
     * @return Collection
     */
    public function getServiceList($garageId, $params = null)
    {
        return Garage::find($garageId)->services()->with('service')->with('brand')
            ->when(isset($params['segment']), function ($q) use ($params) {
                return $q->where('segment', $params['segment']);
            })->when(isset($params['type']), function ($q) use ($params) {
                return $q->where('type', $params['type']);
            })->when(isset($params['category']), function ($q) use ($params) {
                return $q->where('category', $params['category']);
            })->get();
    }



    public function getServiceById($garageServiceId)
    {
        # code...
    }

    /**
     * saveGarageService
     *
     * @param  array $data
     * @return int|bool
     */
    public function saveGarageService($data)
    {
        try {
            $garageService = Gservice::findOrNew($data["id"]);
            $garageService->type = $data["type"];
            $garageService->segment = $data["segment"];
            $garageService->garage_id = $data["garage_id"];
            $garageService->service_id = $data["service_id"];
            $garageService->category = $data["category"];
            $garageService->brand_id = $data["brand"];
            $garageService->model = $data["model"];
            $garageService->price = $data["price"];
            $garageService->save();
            return $garageService->id;
        } catch (QueryException $ex) {
            Log::error($ex);
            return false;
        }
    }


    /**
     * getGarageServiceById
     *
     * @param  int $serviceId
     * @return Collection
     */
    public function getGarageServiceById($serviceId)
    {
        $result = false;
        try {
            $result = Gservice::with('service')
                ->with('brand')
                ->findOrFail($serviceId);
        } catch (Exception $ex) {
            Log::error($ex);
        } finally {
            return $result;
        }
    }


    /**
     * deleteGarageService
     *
     * @param  int $serviceId
     * @return void
     */
    public function deleteGarageService($serviceId)
    {
        Gservice::findOrFail($serviceId)->delete();
    }


    /**
     * getGaragePoolBySegment
     *
     * @param  mixed $garageId
     * @param  mixed $segment
     * @return void
     */
    public function getGaragePoolBySegment($garageId, $segment)
    {
        $selected = GarageService::whereGarageId($garageId)
            ->whereSegment($segment)
            ->get();
        $services = Service::where('segment', $segment)
            ->where('status', 1)
            ->orWhereNull('segment')
            ->select("id", "name", "type", "segment")->get();

        $pool = [];
        foreach (static::TYPES as $type) {
            $index = strtolower($type);
            $pool[$index] = $this->getItemsByType($services, $selected, $type);
        }
        return $pool;
    }


    /**
     * saveGaragePool
     *
     * @param  int $garageId
     * @param  string $segment
     * @param  mixed $pool
     * @return void
     */
    public function saveGaragePool(int $garageId, String $segment, array $pool)
    {
        DB::transaction(function () use ($garageId, $segment, $pool) {
            // deleting
            GarageService::whereGarageId($garageId)
                ->whereSegment($segment)
                ->delete();
            // getting new selection
            $services = [];
            $service = ["garage_id" => $garageId, "segment" => $segment];
            foreach (static::TYPES as $type) {
                $index = strtolower($type);

                if (!isset($pool[$index])) {
                    continue;
                }

                $group = $pool[$index];
                foreach ($group as $item) {
                    if ($item["select"]) {
                        $service["service_id"] = $item["id"];
                        $service["type"] = $type;
                        $service["category"] = $item["category"] ?: null;
                        $service["brand_id"] =  $item["brand"] ?: null;
                        $service["price"] = $item["price"] ?: 0.0;
                        $service["created_at"] = Carbon::now();
                        $service['updated_at'] = Carbon::now();
                        $services[] = $service;
                    }
                }
            }
            // saving selected services 
            GarageService::insert($services);
        });
    }

    /**
     * getItemsByType
     *
     * @param  mixed $list
     * @param  mixed $selected
     * @param  mixed $type
     * @return []
     */
    private function getItemsByType($list, $selected, $type)
    {
        $data = $this->setSelectedServices($selected, $type);
        $serviceList = Collect($list);
        $list = $serviceList->filter(function ($item) use ($type) {
            return $item->type == $type;
        })->SortByDesc('select');

        $result = [];
        foreach ($list as $item) {
            $filtered = array_values($data->where('service', $item->id)->toArray());
            if (count($filtered) > 0) {
                foreach ($filtered as $new) {
                    $result[] = $this->setPoolItem(
                        true,
                        $item->id,
                        $item->name,
                        $item->segment,
                        $new["brand"],
                        $new["category"],
                        $new["price"]
                    );
                }
            } else {
                $result[] = $this->setPoolItem(
                    false,
                    $item->id,
                    $item->name,
                    $item->segment
                );
            }
        }
        return $result;
    }


    /**
     * setSelectedServices
     *
     * @param  Collect $selected
     * @param  string $type
     * @return Collect
     */
    private function setSelectedServices($selected, $type)
    {
        $selectedList = Collect($selected);
        $data = $selectedList->filter(function ($item) use ($type) {
            return $item->type == $type;
        })->map(function ($item) {
            return collect([
                'service' => $item->service_id,
                'category' => $item->category,
                'brand' => $item->brand_id,
                'price' => $item->price,
                'segment' => $item->segment,
            ]);
        });
        return $data;
    }

    /**
     * setPoolItem
     *
     * @param  bool $select
     * @param  int $id
     * @param  string $name
     * @param  string $segment
     * @param  int|null $brand
     * @param  int|null $category
     * @param  double|null $price
     * @return Collection
     */
    private function setPoolItem($select, $id, $name, $segment, $brand = null, $category = null, $price = 0.0)
    {
        return collect([
            'select' => $select,
            'id' => $id,
            'name' => $name,
            'segment' => $segment,
            'brand' => "$brand",
            'category' => "$category",
            'price' => $price,
        ]);
    }


    /**
     * search
     *
     * @param  mixed[] $filters
     * @return void
     */
    public function search($filters)
    {
        $garages = Garage::where("enable", 1)
            ->when($filters['city'], function ($q) use ($filters) {
                return $q->where('province_id', $filters['city']);
            })
            ->when($filters['zip'], function ($q) use ($filters) {
                return $q->where('zipcode', $filters['zip']);
            })
            ->with([
                "province:id,name",
                "state:id,name",
                "services:garage_id,segment,type",
                "media:garage_id,mime,path"
            ])
            ->get(['id', 'name', 'desc', 'state_id', 'province_id']);

        return $garages;
    }
}
