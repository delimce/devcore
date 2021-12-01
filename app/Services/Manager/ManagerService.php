<?php

namespace App\Services\Manager;

use App\Repositories\CompanyRepository;
use App\Repositories\ManagerRepository;
use App\Services\Commons\EmailNotificationService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ManagerService
{
    /** @var ManagerRepository $managerRepository*/
    private $managerRepository;
    /** @var CompanyRepository $companyRepository */
    private $companyRepository;
    /** @var EmailNotificationService $emailService */
    private $emailService;

    function __construct(
        ManagerRepository        $managerRepository,
        CompanyRepository        $companyRepository,
        EmailNotificationService $emailService
    ) {
        $this->managerRepository = $managerRepository;
        $this->companyRepository = $companyRepository;
        $this->emailService      = $emailService;
    }

    /**
     * checkEmail
     * @param  mixed $email
     * @return bool
     */
    public function checkEmail(string $email)
    {
        $exits = $this->managerRepository->getByFilters(["email" => $email]);
        return (count($exits) > 0);
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
        $manager = $this->managerRepository->getFirstByFilters(["email" => $email]);
        if ($manager) {
            $data = $manager->toArray();
            $data["token"] = $this->managerRepository->getTokenById($data["id"]);
            $result["message"] = __('errors.email.notsent');
            if ($this->emailService->managerResetPassword($data)) {
                $result["ok"] = true;
                $result["message"] = __('manager.email.sent.success');
            }
        }
        return $result;
    }

    /**
     * @param array $credentials
     * @return array
     */
    public function login(array $credentials): array
    {
        $result = ["ok" => false, "message" => "", "token" => ""];
        $user = $this->managerRepository->getFirstByFilters(["email" => $credentials['email']]);

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

        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unit tests';

        # saving log
        $user->access()->create(["ip" => $ip, "agent" => $agent]);

        return $result;
    }


    /**
     * @param array $user
     * @return bool|string
     */
    public function addUser(array $user)
    {
        $password = Hash::make($user['password']);
        try {
            $person = $this->managerRepository->create(
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
     * @param  string $token
     * @return Manager|bool
     */
    public function activateUser($token)
    {
        if (empty($token)) {
            return false;
        }

        $user = $this->managerRepository->getFirstByFilters(["token" => $token]);
        if ($user) {
            // user exits
            $user->token = static::newUserToken();
            $user->verified = 1;
            $user->save();
            return $user;
        }
        return false;
    }

    /**
     * @param mixed $token
     * @return int|bool
     */
    public function getIdFromToken($token)
    {
        $user = $this->managerRepository->getFirstByFilters(["token" => $token, "verified" => 1]);
        if ($user) {
            return $user->id;
        }
        return false;
    }

    /**
     * getUserByToken
     *
     * @param  mixed $token
     * @return Collection|false
     */
    public function getUserByToken(string $token)
    {
        $user = $this->managerRepository->getManagerByToken($token);
        if ($user) {
            if (!$user->company) {
                # set default company
                $newUser = collect($user);
                $newUser->put("company", ["name" => "", "nif" => "", "phone" => ""]);
                return $newUser;
            }
            return $user;
        }
        return false;
    }

    /**
     * @param  mixed $token
     * @return bool
     */
    public function isTokenValid($token): bool
    {
        $user = $this->managerRepository->getFirstByFilters(["token" => $token, "verified" => 1]);
        return !is_null($user);
    }

    /**
     * @param mixed $token
     * @param string $old
     * @param string $new
     * 
     * @return array
     */
    public function changePassword($token, string $old, string $new): array
    {
        $result = ["ok" => false, "message" => ""];
        $data = $this->managerRepository->getFirstByFilters(["token" => $token]);
        if (!Hash::check($old, $data->password)) {
            $result["message"] = __('errors.login.oldpassword');
            return $result;
        }

        $newPassword = Hash::make($new);
        if ($this->managerRepository->updatePassword($token, $newPassword)) {
            $result["ok"] = true;
            $result["message"] = __('commons.password.changed');
        }

        return $result;
    }

    /**
     * @param string $token
     * @param string $password
     * @return bool
     */
    public function changePasswordWithToken(string $token, string $password): bool
    {
        $passwordEncrypted = Hash::make($password);
        $newToken = static::newUserToken();
        $this->managerRepository->changePasswordWithToken($token, $newToken, $passwordEncrypted);
        return true;
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
            $this->companyRepository->firstOrNew($company);
            return true;
        } catch (QueryException $ex) {
            Log::error($ex);
            return false;
        }
    }


    /**
     * @param array $data
     * @return bool
     */
    public function saveManager(array $data): bool
    {
        return $this->managerRepository->saveUserInfo($data);
    }

    /**
     * @return string
     */
    public static function newUserToken(): string
    {
        return Str::orderedUuid();
    }
}
