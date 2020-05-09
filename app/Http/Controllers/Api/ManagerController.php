<?php

namespace App\Http\Controllers\Api;

use App\Models\Manager\Manager;
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
            'password' => 'required',
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
        ], $this->getDefaultMessages());

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


    /**
     * get user data
     */
    public function main(Request $req)
    {
        $token = $req->header('Authorization');
        $user = $this->manager->getUserByToken($token);
        $data = ['user' => $user];
        return $this->okResponse($data);
    }

    public function saveMain(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|max:100',
            'lastname' => 'required|max:100',
            'dni' => 'required|min:6',
            'birthdate' => 'required|date',
            'phone' => 'required|integer',
        ], $this->getDefaultMessages());

        $validate = $this->hasValidationErrors($validator);
        if ($validate) {
            return $this->errorResponse($validate);
        }

        $user = Manager::find($req->id);
        $user->name = $req->name;
        $user->lastname = $req->lastname;
        $user->dni = $req->dni;
        $user->birthdate = $req->birthdate;
        $user->phone = $req->phone;

        if (!$this->manager->saveUserInfo($user)) {
            $data = ['message' => __('errors.save')];
            return $this->errorResponse($data);
        }

        $data = ['message' => __('commons.save.success')];
        return $this->okResponse($data);
    }


    public function changePassword(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'oldpassword' => 'required|min:6',
            'password' => 'required|confirmed|min:6',
        ], $this->getDefaultMessages());

        $validate = $this->hasValidationErrors($validator);
        if ($validate) {
            return $this->errorResponse($validate);
        }

        $token = $req->header('Authorization');
        $old = $req->oldpassword;
        $new = $req->password;
        $result = $this->manager->changePassword($token, $old, $new);
        if (!$result["ok"]) {
            $data = ["message" => $result["message"]];
            return $this->errorResponse($data);
        }
        return $this->okResponse($result);
    }
}
