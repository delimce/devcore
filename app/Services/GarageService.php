<?php

namespace App\Services;

use App\Models\Manager\Garage;
use App\Models\Manager\Network;
use App\Models\Manager\Schedule;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon as Carbon;
use Illuminate\Support\Facades\DB;

class GarageService
{

    /**
     * @return mixed
     */
    public function getNetworks()
    {
        $networks = Network::whereStatus(1)->get();
        return $networks;
    }


    /**
     * @param integer $managerId
     * @return mixed
     */
    public function getGarageByManagerId($managerId)
    {
        return Garage::whereManagerId($managerId)->first();
    }


    /**
     * save garage information
     * @param array $garage
     * @return bool
     */
    public function saveGarage(array $garage)
    {
        try {
            $result = Garage::firstOrNew(
                [
                    'manager_id' => $garage['manager'],
                ]
            );
            $result->name = $garage['name'];
            $result->phone = $garage['phone'];
            $result->address = $garage['address'];
            $result->network_id = (!empty($garage['network_id'])) ? $garage['network_id'] : null;
            $result->address2 = (isset($garage['address2'])) ? $garage['address2'] : "";
            $result->desc = (isset($garage['desc'])) ? $garage['desc'] : "";
            $result->country_id = $garage['country_id'];
            $result->state_id = $garage['state_id'];
            $result->province_id = $garage['province_id'];
            $result->zipcode = $garage['zipcode'];
            $result->save();
            return $result->id;
        } catch (QueryException $ex) {
            Log::error($ex);
            return false;
        }
    }


    /**
     * retrieves schedule
     * @param int $garageId
     * @return mixed
     */
    public function getScheduleById($garageId)
    {
        $response = [];
        $garageSchedule = Schedule::whereGarageId($garageId)->orderBy("day")->get();
        $response["garage"] = $garageId;
        $response["schedule"] = $garageSchedule->toArray();
        return $response;
    }



    /**
     * check garage schedule to saving
     * @param array $schedule

     */
    public function garageScheduleValidation($schedule)
    {
        $result = ["ok" => true, "message" => ""];
        // 7 days of week
        if (count($schedule) < 7) {
            $result["message"] = __('errors.validate.schedule.ndays');
            $result["ok"] = false;
            return $result;
        }



        array_walk($schedule, function ($day, $index) use (&$result) {
            $m1 = Carbon::parse($day["am1"]);
            $m2 = Carbon::parse($day["am2"]);
            $n1 = Carbon::parse($day["pm1"]);
            $n2 = Carbon::parse($day["pm2"]);

            //only morning
            if (
                (!is_null($day["am1"]) && !is_null($day["am2"]) && is_null($day["pm1"]) && is_null($day["pm2"])) &&
                ($m1->greaterThan($m2))
            ) {
                $result["message"] = __('errors.validate.schedule.day', ["day" => $index + 1]);
                $result["ok"] = false;
                return $result;
            }


            //only afternoon
            if (
                (is_null($day["am1"]) && is_null($day["am2"]) && !is_null($day["pm1"]) && !is_null($day["pm2"])) &&
                ($n1->greaterThan($n2))
            ) {
                $result["message"] = __('errors.validate.schedule.day', ["day" => $index + 1]);
                $result["ok"] = false;
                return $result;
            }

            //all day
            if (
                (!is_null($day["am1"]) && is_null($day["am2"]) && is_null($day["pm1"]) && !is_null($day["pm2"])) &&
                ($m1->greaterThan($n2))
            ) {
                $result["message"] = __('errors.validate.schedule.day', ["day" => $index + 1]);
                $result["ok"] = false;
                return $result;
            }

            // all times filled
            if (
                (!is_null($day["am1"]) && !is_null($day["am2"]) && !is_null($day["pm1"]) && !is_null($day["pm2"])) &&
                ($m1->greaterThan($m2) || $m2->greaterThan($n1) || $n1->greaterThan($n2))
            ) {
                $result["message"] = __('errors.validate.schedule.day', ["day" => $index + 1]);
                $result["ok"] = false;
                return $result;
            }
        });

        return $result;
    }

    /**
     * saving schedule
     * @param int $garageId
     * @param array $schedule
     */
    public function saveGarageSchedule($garageId, $schedule)
    {
        DB::transaction(function () use ($garageId, $schedule) {
            Schedule::wheregarageId($garageId)->delete();
            array_walk($schedule, function ($day, $index) use ($garageId) {
                Schedule::create([
                    'garage_id' => $garageId,
                    'day' => $index,
                    'am1' => ($day["am1"]) ? $day["am1"] : null,
                    'am2' => ($day["am2"]) ? $day["am2"] : null,
                    'pm1' => ($day["pm1"]) ? $day["pm1"] : null,
                    'pm2' => ($day["pm2"]) ? $day["pm2"] : null,
                ]);
            });
        });
    }
}
