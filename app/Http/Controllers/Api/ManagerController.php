<?php

namespace App\Http\Controllers\Api;

use App\Models\Manager\Manager;
use App\Repositories\ManagerRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ManagerController extends ApiController
{
    protected $manager;

    public function __construct(ManagerRepository $user)
    {
        $this->manager = $user;
    }


    /**
     * @return string
     */
    public function index()
    {
        return "Manager " . env('APP_NAME');
    }


    /**
     * checkEmail
     * @param string $email
     * @return JsonResponse
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
     * @param Request $req
     * @return JsonResponse
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
     * resetPassword
     *
     * @param  mixed $req
     * @return void
     */
    public function resetSendMessage(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'email' => 'required|email|exists:App\Models\Manager\Manager,email'
        ], $this->getDefaultMessages());

        $validate = $this->hasValidationErrors($validator);
        if ($validate) {
            return $this->errorResponse($validate);
        }

        $result = $this->manager->resetPasswordMessage($req->email);
        if (!$result["ok"]) {
            $data = ["message" => $result["message"]];
            return $this->errorResponse($data, 403);
        }
        return $this->okResponse(["message" => $result["message"]]);
    }


    /**
     * resetPassword
     *
     * @param  Request $req
     * @return void
     */
    public function resetPassword(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'token' => 'required|exists:App\Models\Manager\Manager,token',
            'password' => 'required|confirmed|min:6',
        ], $this->getDefaultMessages());

        $validate = $this->hasValidationErrors($validator);
        if ($validate) {
            return $this->errorResponse($validate);
        }

        if ($this->manager->changePasswordWithToken($req->token, $req->password)) {
            return $this->okResponse(["message" => __('commons.password.changed')]);
        }
    }


    /**
     * @param Request $req
     * @return JsonResponse
     */
    public function signUp(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|max:120',
            'lastname' => 'required|max:100',
            'password' => 'required|min:6',
            'phone' => 'required|integer',
            'email' => 'required|email|unique:manager',
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
     * @param Request $req
     * @return JsonResponse
     */
    public function main(Request $req)
    {
        $token = $req->header('Authorization');
        $user = $this->manager->getUserByToken($token);
        $data = ['user' => $user];
        return $this->okResponse($data);
    }

    /**
     * @param Request $req
     * @return JsonResponse
     */
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


    /**
     * @param Request $req
     * @return JsonResponse
     */
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
            return $this->errorResponse($data, 401);
        }
        return $this->okResponse($result);
    }


    /**
     * @param Request $req
     * @return JsonResponse
     */
    public function saveCompany(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'manager_id' => 'required|integer',
            'name' => 'required|max:140',
            'nif' => 'required|min:6',
            'phone' => 'required|integer',
        ], $this->getDefaultMessages());

        $validate = $this->hasValidationErrors($validator);
        if ($validate) {
            return $this->errorResponse($validate);
        }

        $company = [
            "manager" => $req->manager_id,
            "name" => $req->name,
            "nif" => $req->nif,
            "phone" => $req->phone,
        ];

        if (!$this->manager->saveManagerCompany($company)) {
            $data = ['message' => __('errors.save')];
            return $this->errorResponse($data);
        }

        $data = ['message' => __('commons.save.success')];
        return $this->okResponse($data);
    }
}
