<?php

namespace App\Services\Garage;

use App\Repositories\GarageRepository;
use Carbon\Carbon as Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class GarageOperationService
{

    const SCHEDULE_ERROR = 'errors.validate.schedule.day';

    public function __construct(
        private GarageRepository $garageRepository
    ) {
    }

    /**
     * @param array $data
     * @return bool
     */
    public function saveGarage(array $data): bool
    {
        try {
            $garage = $this->garageRepository->create($data);
            return $garage->id;
        } catch (QueryException $ex) {
            Log::error($ex);
            return false;
        }
    }

    /**
     * @param int $garageId
     * @return array
     */
    public function getScheduleById(int $garageId): array
    {
        $response = [];
        $garageSchedule = $this->garageRepository->getGarageSchedule($garageId);
        $response["garage"] = $garageId;
        $response["schedule"] = $garageSchedule->toArray();
        return $response;
    }

    /**
     * @param int $garageId
     * @param array $schedule
     * @throws QueryException
     * @return void
     */
    public function saveGarageSchedule(int $garageId, array $schedule)
    {
        $this->garageRepository->saveSchedule($garageId, $schedule);
    }

    /**
     * check garage schedule to saving
     * @param array $schedule
     * @return array
     */
    public function garageScheduleValidation(array $schedule): array
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
                $result["message"] = __(static::SCHEDULE_ERROR, ["day" => $index + 1]);
                $result["ok"] = false;
                return $result;
            }


            //only afternoon
            if (
                (is_null($day["am1"]) && is_null($day["am2"]) && !is_null($day["pm1"]) && !is_null($day["pm2"])) &&
                ($n1->greaterThan($n2))
            ) {
                $result["message"] = __(static::SCHEDULE_ERROR, ["day" => $index + 1]);
                $result["ok"] = false;
                return $result;
            }

            //all day
            if (
                (!is_null($day["am1"]) && is_null($day["am2"]) && is_null($day["pm1"]) && !is_null($day["pm2"])) &&
                ($m1->greaterThan($n2))
            ) {
                $result["message"] = __(static::SCHEDULE_ERROR, ["day" => $index + 1]);
                $result["ok"] = false;
                return $result;
            }

            // all times filled
            if (
                (!is_null($day["am1"]) && !is_null($day["am2"]) && !is_null($day["pm1"]) && !is_null($day["pm2"])) &&
                ($m1->greaterThan($m2) || $m2->greaterThan($n1) || $n1->greaterThan($n2))
            ) {
                $result["message"] = __(static::SCHEDULE_ERROR, ["day" => $index + 1]);
                $result["ok"] = false;
                return $result;
            }
        });

        return $result;
    }
}
