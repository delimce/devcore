<?php

namespace App\Http\Controllers\Api;

use App\Services\Manager\UserService;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

use Validator;

class ManagerController extends BaseController
{
    protected $manager;

    public function __construct(UserService $user)
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
            return response()->json(['status' => 'error', 'message' => "param email not found"], 400);
        }

        return response()->json(['status' => 'ok', 'data' =>  $this->manager->checkEmail($email)]);
    }


    /**
     * signUp
     * @param  Request $req
     * @return void
     */
    public function signUp(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|max:200',
            'password' => 'required|min:6',
            'tlf' => 'required|integer',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 'error', 'message' => $error], 400);
        }

        $newUser = [
            'name' => $req->name,
            'email' => $req->email,
            'tlf' => $req->tlf,
            'password' => $req->password,
        ];

        return response()->json(['status' => 'ok', 'message' => $this->manager->addUser($newUser)]);
    }
}
