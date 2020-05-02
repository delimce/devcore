<?php

namespace App\Http\Controllers\Api;

use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Laravel\Lumen\Routing\Controller as BaseController;

class ApiController extends BaseController
{

    const STATUS_ERROR = 'error';
    const STATUS_OK = 'ok';

    /**
     *  @param data data payload 
     */
    protected function okResponse($data)
    {
        return response()->json(['status' => static::STATUS_OK, 'info' => $data]);
    }

    /**
     * @param data data payload 
     * @param code default 400 client error
     */
    protected function errorResponse(array $data, int $code = 400)
    {
        return response()->json(['status' => static::STATUS_ERROR, "info" => $data], $code);
    }



    /**
     * @param ValidationValidator $validator
     * @return array|bool
     */
    protected function hasValidationErrors(ValidationValidator $validator)
    {
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return  ["message" => $error];
        }

        return false;
    }

    protected function getDefaultMessages(){
        return [
            'required' => __('errors.validate.require'),
            'email' => __('errors.validate.invalid'),
            'min' => __('errors.validate.min'),
            'max' => __('errors.validate.max'),
        ];
    }
}