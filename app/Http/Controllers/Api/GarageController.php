<?php

namespace App\Http\Controllers\Api;

use App\Decorators\GarageServiceDecorator;
use App\Repositories\GarageRepository;
use App\Repositories\ManagerRepository;
use App\Repositories\MediaRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GarageController extends ApiController
{
    protected $garage;
    protected $manager;
    protected $media;
    protected $token;

    public function __construct(
        GarageRepository $garage,
        ManagerRepository $manager,
        MediaRepository $media,
        Request $req
    ) {
        $this->token = $req->header('Authorization');
        $this->garage = $garage;
        $this->manager = $manager;
        $this->media = $media;
    }


    /**
     * save information
     * @param Request $req
     * @return JsonResponse
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

        $result = $this->garage->saveGarage($garage);
        if (!$result) {
            $data = ["message" => "error"];
            return $this->errorResponse($data, 403);
        }

        $data = ["message" => __('commons.save.success'), "garageId" => $result];
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
     * @param Request $req
     * @return JsonResponse
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
     * @param Request $req
     * @return JsonResponse
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

            $result = $this->media->saveGarageMedia($file, $metadata);
            if (!$result) {
                return $this->errorResponse(["message" => "upload error"], 403);
            }
            return $this->okResponse(["message" => "uploaded"]);
        }
        return $this->errorResponse(["message" => "error"], 403);
    }


    /**
     * get garage media
     * @param $garageId
     * @return JsonResponse
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
     * @param Request $req
     * @return JsonResponse
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

        $result =   $this->media->removeGarageMediaFile([
            "garage_id" => $req->garage,
            "path" => $req->path,
        ]);

        if (!$result) {
            return $this->errorResponse(["message" => "not found"], 403);
        }

        return $this->okResponse(["message" => "ok"]);
    }


    /**
     * getSegments
     * @return void
     */
    public function getSegments(GarageServiceDecorator $decorator)
    {
        $data = $decorator->getCarSegments();
        return $this->okResponse(["list" => $data]);
    }
}
