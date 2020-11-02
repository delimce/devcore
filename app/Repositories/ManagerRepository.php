<?php

namespace App\Repositories;

use App\Models\Manager\Company;
use App\Models\Manager\Manager;
use App\Services\EmailNotificationService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ManagerRepository
{

    protected $emailService;


    public function __construct(EmailNotificationService $emailService)
    {
        $this->emailService = $emailService;
    }


    /**
     * getById
     *
     * @param  int  $managerId
     * @return Collection
     */
    public function getById(int $managerId)
    {
        $manager = Manager::find($managerId);
        return $manager;
    }

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
     * resetPassword
     *
     * @param  string $email
     * @return array
     */
    public function resetPasswordMessage(string $email)
    {
        $result = ["ok" => false, "message" => __('errors.login.email')];
        $manager = Manager::whereEmail($email)->first();
        if ($manager) {
            $data = $manager->toArray();
            $data["token"] = $this->getTokenById($data["id"]);
            $result["message"] = __('errors.email.notsent');
            if ($this->emailService->managerResetPassword($data)) {
                $result["ok"] = true;
                $result["message"] = __('manager.email.sent.success');
            }
        }
        return $result;
    }



    /**
     * changePasswordWithToken
     * for restore password function
     * 
     * @param  string $token
     * @param  string $password
     * @return bool
     */
    public function changePasswordWithToken($token, $password)
    {
        Manager::where('token', $token)
            ->update([
                'token' => static::newUserToken(),
                'password' => Hash::make($password)
            ]);

        return true;
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

        # user logged
        $result["ok"] = true;
        $result["token"] = static::newUserToken();
        $user->token = $result["token"];
        $user->save();

        # saving log
        $user->access()->create(
            [
                "ip" => $_SERVER['REMOTE_ADDR'],
                "agent" => $_SERVER['HTTP_USER_AGENT']
            ]
        );

        return $result;
    }



    /**
     * addUser
     *
     * @param  mixed $user
     * @return string|bool
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

        if (!$this->emailService->managerSignUp($person)) {
            return false;
        }
        return __('manager.created');
    }



    /**
     * isTokenvalid
     * valid token of verified user
     *
     * @param  mixed $token
     * @return void
     */
    public static function isTokenvalid($token)
    {
        $user = Manager::whereToken($token)->whereVerified(1)->first();
        return !is_null($user);
    }


    /**
     * valid token of verified user
     * @param string $token
     * @return bool|int
     */
    public function getIdFromToken($token)
    {
        $user = Manager::whereToken($token)->whereVerified(1)->first();
        if ($user) {
            return $user->id;
        }
        return false;
    }



    /**
     * getTokenById
     *
     * @param  int $userID
     * @return string
     */
    public function getTokenById($userID)
    {
        return DB::table('manager')->where('id', $userID)->value('token');
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
     * getUserByToken
     *
     * @param  mixed $token
     * @return array|false
     */
    public function getUserByToken(string $token)
    {
        $user = Manager::whereToken($token)->whereVerified(1)->first();
        if (!is_null($user)) {
            $data = $user->toArray();
            $company = Company::whereManagerId($data["id"])->first();
            $data["company"] = is_null($company) ? ["name" => "", "nif" => "", "phone" => ""] : $company->toArray();
            return $data;
        }
        return false;
    }



    /**
     * saveUserInfo
     *
     * @param  mixed $info
     * @return bool
     */
    public function saveUserInfo(Manager $info)
    {
        try {
            $info->save();
            return true;
        } catch (QueryException $ex) {
            Log::error($ex);
            return false;
        }
    }


    /**
     * changePassword
     *
     * @param  string $token
     * @param  string $old
     * @param  string $new
     * @return void
     */
    public function changePassword($token, $old, $new)
    {
        $result = ["ok" => false, "message" => ""];
        $data = DB::table('manager')->whereToken($token)->first();
        if (!Hash::check($old, $data->password)) {
            $result["message"] = __('errors.login.oldpassword');
            return $result;
        }

        if ($this->updatePassword($token, $new)) {
            $result["ok"] = true;
            $result["message"] = __('commons.password.changed');
        }

        return $result;
    }


    /**
     * updatePassword
     *
     * @param  string $token
     * @param  string $password
     * @return bool
     */
    public function updatePassword(string $token, string $password)
    {
        try {
            Manager::whereToken($token)->update(["password" => Hash::make($password)]);
            return true;
        } catch (QueryException $ex) {
            Log::error($ex);
            return false;
        }
    }



    /**
     * saveManagerCompany
     *
     * @param  mixed $company
     * @return bool
     */
    public function saveManagerCompany(array $company)
    {
        try {
            $result = Company::firstOrNew(
                [
                    'manager_id' => $company['manager'],
                ]
            );
            $result->name = $company['name'];
            $result->nif = $company['nif'];
            $result->phone = $company['phone'];
            $result->save();
            return true;
        } catch (QueryException $ex) {
            Log::error($ex);
            return false;
        }
    }


    /**
     * generates manager token
     * @return string
     */
    public static function newUserToken()
    {
        return Str::orderedUuid();
    }
}
