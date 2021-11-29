<?php

namespace App\Http\Controllers\Api;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
{

    protected $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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
        $logged = $this->userRepository->doLogin($credentials);

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

        $user = $this->userRepository->createUser($data);
        return $this->okResponse($user);
    }
}
