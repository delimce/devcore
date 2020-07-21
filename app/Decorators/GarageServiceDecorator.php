<?php

namespace App\Decorators;

use App\Repositories\GarageRepository;
use App\Utils\Utilities;
use Illuminate\Database\Eloquent\Collection;

class GarageServiceDecorator
{
    protected $garageService;


    public function __construct(GarageRepository $garage)
    {
        $this->garageService = $garage;
    }


    /**
     * retrieve car segments
     * @param array
     */
    public function getCarSegments()
    {
        $segments = $this->garageService->getCarSegments();
        return $this->useTermsToCodes($segments);
    }

    /**
     * getServiceTypes
     * @return array
     */
    public function getServiceTypes()
    {
        $types = $this->garageService->getServiceTypes();
        return $this->useTermsToCodes($types);
    }


    /**
     * getServiceCategories
     * @return array
     */
    public function getServiceCategories()
    {
        $categories = $this->garageService->getServiceCategories();
        return $this->useTermsToCodes($categories);
    }

    public function getServiceList($garageId, $params)
    {
        $list = $this->garageService->getServiceList($garageId, $params);

        return $list->map(function ($item) {
            return [
                "id" => $item->id,
                "segment" => Utilities::getTermTranslated($item->segment),
                "type" => Utilities::getTermTranslated($item->type),
                "category" => Utilities::getTermTranslated($item->category),
                "service" => $item->service->desc,
                "brand" => ($item->brand) ? $item->brand->name : "",
                "price" => $item->price . "€",
            ];
        });
    }


    /**
     * useTermsToCodes
     * get array of terms translated
     * @param  Collection $result
     * @return array
     */
    protected function useTermsToCodes($result)
    {
        return $result->map(function ($item) {
            return [
                "id" => $item->code,
                "desc" => Utilities::getTermTranslated($item->code)
            ];
        });
    }
}
