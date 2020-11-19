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
        return $this->okResponse(["list"=>$result]);
    }
}
