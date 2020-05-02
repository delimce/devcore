<?php

namespace App\Http\Controllers\Api;
use App\Services\ManagerService;
use Illuminate\Http\Request;

use Validator;

class ManagerController extends ApiController
{
    protected $manager;

    public function __construct(ManagerService $user)
    {
        $this->manager = $user;
    }


    public function index()
    {
        return "Manager " . env('APP_NAME');
    }


    /**
     * checkEmail
     * @param  string $email
     * @return void
     */
    public function checkEmail($email)
    {
        if (empty($email)) {
            $data = ["message" => "param email not found"];
            return $this->errorResponse($data);
        }

        return $this->okResponse($this->manager->checkEmail($email));
    }

    /**
     * login
     * @param  Request $req
     * @return void
     */
    public function doLogin(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], $this->getDefaultMessages());

        $validate = $this->hasValidationErrors($validator);
        if ($validate) {
            return $this->errorResponse($validate);
        }

        $credentials = ["email" => $req->email, "password" => $req->password];
        $logged = $this->manager->login($credentials);

        if (!$logged["ok"]) {
            $data = ["message" => $logged["message"]];
            return $this->errorResponse($data);
        }

        return $this->okResponse($logged);
    }


    /**
   
     */
    public function signUp(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|max:120',
            'lastname' => 'required|max:100',
            'password' => 'required|min:6',
            'phone' => 'required|integer',
            'email' => 'required|email',
        ],$this->getDefaultMessages());

        $validate = $this->hasValidationErrors($validator);
        if ($validate) {
            return $this->errorResponse($validate);
        }

        $newUser = [
            'name' => $req->name,
            'lastname' => $req->lastname,
            'email' => $req->email,
            'phone' => $req->phone,
            'password' => $req->password,
        ];

        $result = $this->manager->addUser($newUser);
        if (!$result) {
            $data = ['message' => __('errors.signup')];
            return $this->errorResponse($data);
        }

        $data = ['message' => $result];
        return $this->okResponse($data);
    }
}
