<?php

namespace App\Services;

use App\Models\Manager\Manager;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ManagerService
{
    const TOKEN_LENGHT = 40;

    /**
     * checkEmail
     * @param  mixed $email
     * @return bool
     */
    public function checkEmail(string $email)
    {
        $exits = Manager::whereEmail($email)->count();
        return ($exits > 0);
    }


    /**
     *  @param  Manager $user
     */
    public function signUpNotify(Manager $user)
    {

        Mail::send('emails.registered', ["user" => $user], function ($m) use ($user) {
            $title = __('manager.email.registered.title');
            $m->to($user->email, $user->fullName())->subject($title);
        });
    }


    /**
     * do login
     * @param array
     * @return array
     */
    public function login(array $credentials)
    {
        $result = ["ok" => false, "message" => "", "token" => ""];
        $user = Manager::whereEmail($credentials['email'])->first();
        if (is_null($user)) {
            $result["message"] = __('errors.login.email');
            return $result;
        }

        if (!$user->verified) {
            $result["message"] = __('errors.login.disable');
            return $result;
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            $result["message"] = __('errors.login.password');
            return $result;
        }

        ///user logged
        $result["ok"] = true;
        $result["token"] = static::newUserToken();
        $user->token = $result["token"];
        $user->save();
        return $result;
    }


    /**
     * @return bool|array
     */
    public function addUser(array $user)
    {

        $password = Hash::make($user['password']);
        try {
            $person = Manager::create(
                [
                    'name' => $user['name'],
                    'lastname' => $user['lastname'],
                    'email' => $user['email'],
                    'phone' => $user['phone'],
                    'password' => $password,
                    'token' => static::newUserToken(),
                ]
            );
        } catch (QueryException $ex) {
            Log::error($ex);
            return false;
        }

        //toEmail 
        $this->signUpNotify($person);

        return __('manager.created');
    }


    /**
     * @param  string $token
     * @return Manager|bool
     */
    public function activateUser($token)
    {
        if (empty($token)) {
            return false;
        }

        $user = Manager::whereToken($token)->first();
        if (!is_null($user)) {
            // user exits
            $user->token = static::newUserToken();
            $user->verified = 1;
            $user->save();
            return $user;
        }

        return false;
    }


    /**
     * generates manager token
     * @return string
     */
    public static function newUserToken()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}