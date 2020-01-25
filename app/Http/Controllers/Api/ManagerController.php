<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

use Validator;

class ManagerController extends BaseController
{


    public function index()
    {
        return "Manager " . env('APP_NAME');
    }


    public function checkEmail($email)
    {
        if (empty($email)) {
            return response()->json(['status' => 'error', 'message' => "param email not found"], 400);
        }
    }

    public function signUp(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|max:200',
            'password' => 'required|min:6',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 'error', 'message' => $error], 400);
        }
    }
}
