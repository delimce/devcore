<?php

namespace App\Http\Controllers\Api;

use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
{

    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    /**
     * login
     *
     * @param  Request $req
     * @return mixed
     */
    public function login(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], $this->getDefaultMessages());

        $validate = $this->hasValidationErrors($validator);
        if ($validate) {
            return $this->errorResponse($validate);
        }

        $credentials = ["email" => $req->email, "password" => $req->password];
        $logged = $this->userService->doLogin($credentials);

        if (!$logged["ok"]) {
            $data = ["message" => $logged["message"]];
            return $this->errorResponse($data, 401);
        }

        return $this->okResponse($logged);
    }


    /**
     * createNew
     *
     * @param  Request $req
     * @return mixed
     */
    public function createNew(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|min:2',
            'lastname' => 'required|min:2',
            'email' => 'required|email|unique:user',
            'password' => 'required|confirmed|min:8',
        ], $this->getDefaultMessages());

        $validate = $this->hasValidationErrors($validator);
        if ($validate) {
            return $this->errorResponse($validate);
        }

        $data = [
            "name" => $req->name,
            "lastname" => $req->lastname,
            "email" => $req->email,
            "password" => $req->password,
            "active" => 1, # @todo: temp
        ];

        $user = $this->userService->createUser($data);
        return $this->okResponse($user);
    }
}
