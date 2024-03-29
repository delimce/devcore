<?php

namespace App\Http\Controllers\Api;

use App\Decorators\GarageServiceDecorator;
use App\Repositories\GarageRepository;
use App\Services\Commons\MediaFileService;
use App\Services\Garage\GarageOperationService as GarageService;
use App\Services\Manager\ManagerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GarageController extends ApiController
{
    protected $token;

    public function __construct(
        Request          $req,
        private GarageRepository $garage,
        private GarageService    $garageService,
        private ManagerService   $manager,
        private MediaFileService $media,
    ) {
        $this->token = $req->header('Authorization');
    }


    /**
     * save information
     * @param Request $req
     * @return JsonResponse
     */
    public function saveGarage(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name'        => 'required|max:140',
            'phone'       => 'required|integer|min:3',
            'address'     => 'required|min:9',
            'country_id'  => 'required|integer',
            'state_id'    => 'required|integer',
            'province_id' => 'required|integer',
            'zipcode'     => 'required|min:4',
        ], $this->getDefaultMessages());

        $validate = $this->hasValidationErrors($validator);
        if ($validate) {
            return $this->errorResponse($validate);
        }

        $new = [
            "manager"     => $this->manager->getIdFromToken($this->token),
            "name"        => $req->name,
            "phone"       => $req->phone,
            "country_id"  => $req->country_id,
            "state_id"    => $req->state_id,
            "province_id" => $req->province_id,
            "address"     => $req->address,
            "zipcode"     => $req->zipcode,
        ];

        if ($req->has('address2')) {
            $new["address2"] = $req->address2;
        }

        if ($req->has('network_id')) {
            $new["network_id"] = $req->network_id;
        }

        if ($req->has('desc')) {
            $new["desc"] = $req->desc;
        }

        $result = $this->garageService->saveGarage($new);
        if (!$result) {
            $data = ["message" => "error"];
            return $this->errorResponse($data, 403);
        }

        $data = ["message" => __('commons.save.success'), "garageId" => $result];
        return $this->okResponse($data);
    }

    /**
     * retrieves garage information
     * @return
     */
    public function getGarageInfo()
    {
        $userId = $this->manager->getIdFromToken($this->token);
        $info = $this->garage->getGarageByManagerId($userId);
        return $this->okResponse($info);
    }

    /**
     * retrieves garage networks
     */
    public function getNetworks()
    {
        return $this->okResponse($this->garage->getNetworks());
    }

    /**
     * retrieves garage schedule (monday - sunday)
     */
    public function getSchedule()
    {
        $userId   = $this->manager->getIdFromToken($this->token);
        $info     = $this->garage->getGarageByManagerId($userId);
        $schedule = [];
        if ($info) {
            $schedule = $this->garageService->getScheduleById($info->id);
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
            'garage'   => 'required|integer',
            'schedule' => 'required',
        ], $this->getDefaultMessages());

        $validate = $this->hasValidationErrors($validator);
        if ($validate) {
            return $this->errorResponse($validate);
        }

        $validSchedule = $this->garageService->garageScheduleValidation($req->schedule);
        if (!$validSchedule["ok"]) {
            return $this->errorResponse($validSchedule);
        }

        $this->garageService->saveGarageSchedule($req->garage, $req->schedule);
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
            'file'   => 'required|file',
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
                    "original"  => $file->getClientOriginalName(),
                    "mime"      => $file->getMimeType(),
                    "size"      => $file->getSize(),
                    "extension" => $file->getClientOriginalExtension()
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
            'path'   => 'required',
        ], $this->getDefaultMessages());

        $validate = $this->hasValidationErrors($validator);
        if ($validate) {
            return $this->errorResponse($validate);
        }

        $result =   $this->media->removeGarageMediaFile([
            "garage_id" => $req->garage,
            "path"      => $req->path,
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
