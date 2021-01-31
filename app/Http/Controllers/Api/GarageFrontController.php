<?php

namespace App\Http\Controllers\Api;

use App\Repositories\GarageRepository;
use Illuminate\Http\Request;

class GarageFrontController extends ApiController
{
    protected $garage;

    public function __construct(
        GarageRepository $garage
    ) {
        $this->garage = $garage;
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
        $garage = $this->garage->getDetailsById($id);
        if (!$garage) {
            return  $this->errorResponse([
                "message" => __('errors.404')
            ], 404);
        }
        return $this->okResponse($garage);
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
        $services = $this->garage->findService($criteria);
        return $this->okResponse(["list" => $services]);
    }
}
