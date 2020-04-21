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
     * signUp
     * @param  Request $req
     * @return void
     */
    public function signUp(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|max:120',
            'lastname' => 'required|max:100',
            'password' => 'required|min:6',
            'phone' => 'required|integer',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            $data = ["message" => $error];
            return $this->errorResponse($data);
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
