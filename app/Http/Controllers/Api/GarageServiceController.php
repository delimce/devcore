<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\ManagerRepository;
use App\Decorators\GarageServiceDecorator;
use App\Repositories\GarageServiceRepository;

class GarageServiceController extends ApiController
{
    protected $garage;
    protected $manager;
    protected $token;

    public function __construct(
        GarageServiceRepository $garage,
        ManagerRepository $manager,
        Request $req
    ) {
        $this->token = $req->header('Authorization');
        $this->garage = $garage;
        $this->manager = $manager;
    }


    /**
     * getServicesTypes
     * @param GarageServiceDecorator $decorator
     * @return JsonResponse
     */
    public function getServicesTypes(GarageServiceDecorator $decorator)
    {
        $data = $decorator->getServiceTypes();
        return $this->okResponse(["list" => $data]);
    }

    /**
     * getServicesCategories
     * @param GarageServiceDecorator $decorator
     * @return JsonResponse
     */
    public function getServicesCategories(GarageServiceDecorator $decorator)
    {
        $data = $decorator->getServiceCategories();
        return $this->okResponse(["list" => $data]);
    }

    /**
     * getServiceCatalog
     *
     * @param  Request $req
     * @return JsonResponse
     */
    public function getServiceCatalog(Request $req)
    {
        $segment = "";
        $type = "";

        if ($req->has("segment")) {
            $segment = $req->segment;
        }

        if ($req->has("type")) {
            $type = $req->type;
        }

        $data = $this->garage->getServicesCatalogByQuery($segment, $type);
        return $this->okResponse(["list" => $data]);
    }

    /**
     * getServiceBrands
     *
     * @param  Request $req
     * @return void
     */
    public function getServiceBrands(Request $req)
    {

        $type = "";
        $category = "";

        if ($req->has("type")) {
            $type = $req->type;
        }

        if ($req->has("category")) {
            $category = $req->category;
        }

        $data = $this->garage->getServiceBrandsByQuery($type, $category);
        return $this->okResponse(["list" => $data]);
    }

    /**
     * getServices
     *
     * @param  int $garageId
     * @param  Request $req
     * @param  GarageServiceDecorator $decorator
     * @return JsonResponse
     */
    public function getServices(int $garageId, Request $req, GarageServiceDecorator $decorator)
    {
        $params = [];

        if ($req->has("segment")) {
            $params["segment"] =  $req->segment;
        }

        if ($req->has("type")) {
            $params["type"] =  $req->type;
        }

        if ($req->has("category")) {
            $params["category"] = $req->category;
        }

        $results = $decorator->getServiceList($garageId, $params);
        return $this->okResponse(["list" => $results]);
    }

    /**
     * getServiceById
     *
     * @param  int $serviceId
     * @return JsonResponse
     */
    public function getServiceById($serviceId)
    {
        if (empty($serviceId)) {
            return $this->errorResponse(["message" => "wrong service id"]);
        }

        $result = $this->garage->getGarageServiceById($serviceId);

        if (!$result) {
            return $this->errorResponse(["message" => "service not found"], 403);
        }

        return $this->okResponse($result);
    }

    /**
     * saveService
     * @param  Request $req
     * @return JsonResponse
     */
    public function saveService(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'garage_id' => 'required|integer',
            'service_id' => 'required|integer',
            'segment' => 'required',
            'type' => 'required',
            'price' => 'required|numeric|min:0.1',
        ], $this->getDefaultMessages());

        $validate = $this->hasValidationErrors($validator);
        if ($validate) {
            return $this->errorResponse($validate);
        }

        $data = [];
        $data["id"] = ($req->has("id")) ? $req->id : 0;
        $data["garage_id"] = $req->garage_id;
        $data["service_id"] = $req->service_id;
        $data["category"] = $req->category;
        $data["segment"] = $req->segment;
        $data["type"] = $req->type;
        $data["brand"] = ($req->brand) ? $req->brand : null;
        $data["model"] = $req->model;
        $data["price"] = $req->price;

        $result = $this->garage->saveGarageService($data);
        if (!$result) {
            return $this->errorResponse(["message" => "error"], 403);
        }

        return $this->okResponse(["id" => $result]);
    }



    /**
     * deleteService
     * @param  mixed $req
     * @return JsonResponse
     */
    public function removeService(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'service_id' => 'required|integer',
        ], $this->getDefaultMessages());

        $validate = $this->hasValidationErrors($validator);
        if ($validate) {
            return $this->errorResponse($validate);
        }

        $this->garage->deleteGarageService($req->service_id);
        return $this->okResponse(["message" => "ok"]);
    }


    /**
     * getPoolBySegment
     *
     * @param  int $garageId
     * @param  string $segment
     * @return JsonResponse
     */
    public function getPoolBySegment(int $garageId, String $segment)
    {
        $result = $this->garage->getGaragePoolBySegment($garageId, $segment);
        return $this->okResponse($result);
    }


    /**
     * savePoolBySegment
     *
     * @param  mixed $req
     * @return JsonResponse
     */
    public function savePoolBySegment(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'garage_id' => 'required|integer',
            'segment' => 'required',
            'pool' => 'required',
        ], $this->getDefaultMessages());

        $validate = $this->hasValidationErrors($validator);
        if ($validate) {
            return $this->errorResponse($validate);
        }

        $this->garage->saveGaragePool($req->garage_id, $req->segment, $req->pool);
        return $this->okResponse(["message" => __('commons.save.success')]);
    }
}
