<?php

namespace App\Http\Controllers\Api;

use App\Services\GarageService;
use App\Services\ManagerService;
use App\Services\MediaService;
use Illuminate\Http\Request;

use Validator;

class GarageController extends ApiController
{
    protected $garage;
    protected $manager;
    protected $media;
    protected $token;

    public function __construct(
        GarageService $garage,
        ManagerService $manager,
        MediaService $media,
        Request $req
    ) {
        $this->token = $req->header('Authorization');
        $this->garage = $garage;
        $this->manager = $manager;
        $this->media = $media;
    }


    /**
     * save information
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


    /**
     * retrives garage information
     * @return
     */
    public function getGarageInfo()
    {
        $userId = $this->manager->getIdFromToken($this->token);
        $info = $this->garage->getGarageByManagerId($userId);
        return $this->okResponse($info);
    }

    /**
     * retrives garage networks 
     */
    public function getNetworks()
    {
        return $this->okResponse($this->garage->getNetworks());
    }

    /**
     * retrives garage schedule (monday - sunday)
     */
    public function getSchedule()
    {
        $userId = $this->manager->getIdFromToken($this->token);
        $info = $this->garage->getGarageByManagerId($userId);
        $schedule = [];
        if ($info) {
            $schedule = $this->garage->getScheduleById($info->id);
        }
        return $this->okResponse($schedule);
    }

    /**
     * saving garage schedules
     */
    public function saveSchedule(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'garage' => 'required|integer',
            'schedule' => 'required',
        ], $this->getDefaultMessages());

        $validate = $this->hasValidationErrors($validator);
        if ($validate) {
            return $this->errorResponse($validate);
        }

        $validSchedule = $this->garage->garageScheduleValidation($req->schedule);
        if (!$validSchedule["ok"]) {
            return $this->errorResponse($validSchedule);
        }

        $this->garage->saveGarageSchedule($req->garage, $req->schedule);
        $data = ["message" => __('commons.save.success')];
        return $this->okResponse($data);
    }


    /**
     * saving garage media
     */
    public function saveMedia(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'garage' => 'required|integer',
            'file' => 'required|file',
        ], $this->getDefaultMessages());

        $validate = $this->hasValidationErrors($validator);
        if ($validate) {
            return $this->errorResponse($validate);
        }

        if ($req->file('file') && $req->file('file')->isValid()) {

            $file = $req->file('file');
            $metadata =
                [
                    "garage_id" => $req->garage,
                    "original" => $file->getClientOriginalName(),
                    "mime" => $file->getMimeType(),
                    "size" => $file->getSize(),
                    "extension" =>  $file->getClientOriginalExtension()
                ];

            $this->media->saveGarageMedia($file, $metadata);
            return $this->okResponse(["message" => "uploaded"]);
        }
        return $this->errorResponse(["message" => "error"], 403);
    }


    /**
     * get garage media
     */
    public function getMedia($garageId)
    {
        if (intval($garageId) == 0) {
            return $this->errorResponse(["message" => "error"], 403);
        }

        $data = $this->media->getGarageFilesFromMedia($garageId);
        return $this->okResponse($data);
    }


    /**
     * delete media file from garage media
     */
    public function removeMedia(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'garage' => 'required|integer',
            'path' => 'required',
        ], $this->getDefaultMessages());

        $validate = $this->hasValidationErrors($validator);
        if ($validate) {
            return $this->errorResponse($validate);
        }

        $data =
            [
                "garage_id" => $req->garage,
                "path" => $req->path,
            ];

        $this->media->removeGarageMediaFile($data);
        return $this->okResponse(["message" => "ok"]);
    }
}
