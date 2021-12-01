<?php

namespace App\Http\Controllers\Api;

use App\Repositories\SupportRepository;
use App\Services\Manager\ManagerService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupportController extends ApiController
{
    protected $support;
    protected $token;
    protected $manager;

    public function __construct(
        SupportRepository $support,
        ManagerService $manager,
        Request $req
    ) {
        $this->support = $support;
        $this->manager = $manager;
        $this->token = $req->header('Authorization');
    }

    /**
     * @param bool $countryId
     * @return JsonResponse
     */
    public function saveRequest(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'type' => 'required|min:1',
            'description' => 'required'
        ], $this->getDefaultMessages());

        $validate = $this->hasValidationErrors($validator);
        if ($validate) {
            return $this->errorResponse($validate);
        }

        try {
            $data = [
                "manager_id" => $this->manager->getIdFromToken($this->token),
                "garage_id" => $req->garage,
                'request_type' => $req->type,
                'description' => $req->description
            ];
            $this->support->saveSupportRequest($data);
            return $this->okResponse(["message" => __('manager.support.save')]);
        } catch (Exception $ex) {
            return $this->errorResponse(__('errors.save'), 500);
        }
    }
}
