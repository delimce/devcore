<?php

namespace App\Repositories;

use App\Models\Manager\Brand;
use App\Models\Manager\Garage;
use App\Models\Manager\GarageService as Gservice;
use App\Models\Manager\GarageService;
use App\Models\Manager\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon as Carbon;
use Exception;

class GarageServiceRepository
{

    const SERVICES_TYPES = ["TYRE", "FILTER", "BATTERY", "CHECK", "OIL", "WORKFORCE", "BRAKE", "AC"];

    /**
     * getServicesCatalogByQuery
     *
     * @param  string $segment
     * @param  string $type
     * @return Collection
     */
    public function getServicesCatalogByQuery($segment, $type): Collection
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
    public function getServiceBrandsByQuery($type, $category): Collection
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
    public function getServiceList($garageId, $params = null): Collection
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
     * @return array
     */
    public function getGaragePoolBySegment($garageId, $segment): array
    {
        $selected = GarageService::whereGarageId($garageId)
            ->whereSegment($segment)
            ->get();
        $services = Service::where('segment', $segment)
            ->where('status', 1)
            ->orWhereNull('segment')
            ->select("id", "name", "type", "segment")->get();

        $pool = [];
        foreach (static::SERVICES_TYPES as $type) {
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
            foreach (static::SERVICES_TYPES as $type) {
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
     * @return string[]
     */
    private function getItemsByType($list, $selected, $type): array
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
     * @return \Illuminate\Support\Collection
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
     * serviceSearch
     *
     * @param  array $criteria
     * @return Collection
     */
    public function findService(array $criteria)
    {
        return Service::where(["status" => 1])
            ->when(isset($criteria['type']), function ($q) use ($criteria) {
                return $q->where('type', $criteria['type']);
            })->when(isset($criteria['segment']), function ($q) use ($criteria) {
                return
                    $q->where(function ($query) use ($criteria) {
                        return
                            $query->where('segment', $criteria['segment'])
                            ->orWhere('segment', null);
                    });
            })->orderBy("name")->get();
    }

}
