<?php

namespace App\Http\Controllers\Api;

use App\Services\GarageService;
use App\Services\ManagerService;
use Illuminate\Http\Request;

use Validator;

class GarageController extends ApiController
{
    protected $garage;
    protected $manager;
    protected $token;

    public function __construct(GarageService $garage, ManagerService $manager, Request $req)
    {
        $this->token = $req->header('Authorization');
        $this->garage = $garage;
        $this->manager = $manager;
    }


    /**
     * 
     */
    public function saveGarage(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|max:140',
            'phone' => 'required|integer',
            'address' => 'required|min:9',
            'country_id' => 'required|integer',
            'state_id' => 'required|integer',
            'province_id' => 'required|integer',
            'zipcode' => 'required|integer|min:3',
        ], $this->getDefaultMessages());

        $validate = $this->hasValidationErrors($validator);
        if ($validate) {
            return $this->errorResponse($validate);
        }

        $garage = [
            "manager" => $this->manager->getIdFromToken($this->token),
            "name" => $req->name,
            "phone" => $req->phone,
            "country_id" => $req->country_id,
            "state_id" => $req->state_id,
            "province_id" => $req->province_id,
            "address" => $req->address,
            "zipcode" => $req->zipcode,
        ];

        if ($req->has('address2')) {
            $garage["address2"] = $req->address2;
        }

        if ($req->has('network_id')) {
            $garage["network_id"] = $req->network_id;
        }

        if ($req->has('desc')) {
            $garage["desc"] = $req->desc;
        }

        if (!$this->garage->saveGarage($garage)) {
            $data = ["message" => "error"];
            return $this->errorResponse($data, 403);
        }

        $data = ["message" => __('commons.save.success')];
        return $this->okResponse($data);
    }


    public function getGarageInfo()
    {
        $userId = $this->manager->getIdFromToken($this->token);
        $info = $this->garage->getGarageByManagerId($userId);
        return $this->okResponse($info);
    }

    public function getNetworks()
    {
        return $this->okResponse($this->garage->getNetworks());
    }
}
