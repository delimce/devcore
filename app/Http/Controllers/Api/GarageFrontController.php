<?php

namespace App\Http\Controllers\Api;

use App\Repositories\GarageRepository;
use App\Repositories\GarageServiceRepository;
use Illuminate\Http\Request;

class GarageFrontController extends ApiController
{
    protected $garage;

    public function __construct(
        GarageRepository $garage,
        GarageServiceRepository $services
    ) {
        $this->garage = $garage;
        $this->services = $services;
    }


    /**
     * mainSearch
     *
     * @param  mixed $req
     * @return void
     */
    public function mainSearch(Request $req)
    {

        $filters = [
            "text" => $req->text ?? "",
            "city" => $req->city ?? "",
            "zip" => $req->zip ?? "",
            "segment" => $req->segment ?? "",
            "type" => $req->type ?? "",
            "service" => $req->service ?? "",
        ];

        # if no filters    
        if (count(array_filter($filters, function ($item) {
            return $item != "";
        })) == 0) {
            return  $this->errorResponse([
                "message" => __('errors.search.nofilters')
            ], 403);
        }


        $result = $this->garage->search($filters);
        return $this->okResponse(["list" => $result]);
    }


    /**
     * getById
     *
     * @param  int $id
     * @return mixed
     */
    public function getById($id)
    {
        $detail = $this->garage->getDetailsById($id);
        if (!$detail) {
            return  $this->errorResponse([
                "message" => __('errors.404')
            ], 404);
        }
        return $this->okResponse($detail);
    }

    /**
     * searchService
     *
     * @param  mixed $req
     * @return JsonResponse
     */
    public function searchServices(Request $req)
    {
        $criteria = [];
        if ($req->has("type")) {
            $criteria["type"] = $req->type;
        }
        if ($req->has("segment")) {
            $criteria["segment"] = $req->segment;
        }
        $services = $this->services->findService($criteria);
        return $this->okResponse(["list" => $services]);
    }
}
