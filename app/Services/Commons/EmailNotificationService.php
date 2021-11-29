<?php

namespace App\Services\Commons;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailNotificationService
{


    /**
     * managerResetPassword
     *
     * @param  array $manager
     * @return bool
     */
    public function managerResetPassword($manager)
    {
        $template = 'emails.manager.reset';
        $title = __('commons.password.reset');
        $data = $manager;
        return $this->dispatch($template, $title, $data);
    }


    /**
     * managerSignUp
     *
     * @param  array $person
     * @return bool
     */
    public function managerSignUp($person)
    {
        $template = 'emails.manager.registered';
        $title =  __('manager.email.registered.title');
        $data = $person;
        return $this->dispatch($template, $title, $data);
    }


    /**
     * sendEmail
     *
     * @param  string $template
     * @param  string $title
     * @param  array $data
     * @return void
     */
    private function dispatch($template, $title, $data)
    {
        $result = false;
        try {
            Mail::send($template, ["data" => $data], function ($m) use ($data, $title) {
                $m->to($data["email"], $data["fullname"])->subject($title);
            });
            $result = true;
        } catch (Exception $ex) {
            Log::error($ex);
        } finally {
            return $result;
        }
    }
}
