<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse as ResponseType;

class JsonResponse
{
    const SUCCESS = 'success';
    const FAILURE = 'failure';

    const STATUS_MESSAGES = [
        'created' => 'Data has been created.',
        'updated' => 'Data has been updated.',
        'deleted' => 'Data has been deleted.',
        'restored' => 'Data has been restored',
        'already_exists' => 'Data already exists',
        'fetched_data' => 'Data has been fetched',
        'unknown_error' => 'Something went wrong.',
        'not_found' => 'Data not found.',
        'access_denied' => "You don't have access to this resource.",
        'invalid_token' => 'Invalid token.',
        'invalid_login' => 'Please enter valid username or email',
        'registered' => 'Account has been created successfully.',
        'cant_delete' => "The server can't process the delete request.",
        'cant_update' => "The server can't process the update request.",
        'no_matches' => 'No matches',
        'incorrect_password' => 'Incorrect password.',
        'incorrect_uuid' => 'Incorrect UUID',
        'authorized' => 'Successfully authorized.',
        'password_reset_link' => 'We have e-mailed your password reset link!',
        'project_not_found' => 'Project not found.'
    ];

    public static function createResponse(
        $status,
        $status_code,
        $model = null,
        $message = '',
        array $additional_data = []
    ): ResponseType
    {
        $response_data = '';
        if($status == self::FAILURE){
            $response_data = [
                'message' => __($message),
                'status' => $status,
                'errors' => [
                    'error' => __($message),
                ]
            ];
        }else
            $response_data = [
                'message' => __($message),
                'status' => $status,
            ];

        $model != null ? $response_data['model'] = $model : false;
        if(!empty($additional_data)) {
            foreach ($additional_data as $key => $val)
                $response_data[$key] = $val;
        }
        return response()->json($response_data,$status_code);
    }

    public static function tMessage($message)
    {
        if(!empty(self::STATUS_MESSAGES[$message])){
            return __(self::STATUS_MESSAGES[$message]);
        }else
            return __($message);
    }

    public static function Fetched($model,$message = self::STATUS_MESSAGES['fetched_data']): ResponseType
    {
        return JsonResponse::createResponse(self::SUCCESS,200,$model,__($message));
    }

    public static function Created($model = null): ResponseType
    {
        return JsonResponse::createResponse(self::SUCCESS,201,$model,self::tMessage('created'));
    }

    public static function Updated($model = null,$message = self::STATUS_MESSAGES['updated']): ResponseType
    {
        return JsonResponse::createResponse(self::SUCCESS,200,$model,__($message));
    }

    public static function Deleted($model = null,$message = self::STATUS_MESSAGES['deleted']): ResponseType
    {
        return JsonResponse::createResponse(self::SUCCESS,200,$model,__($message));
    }

    //FAILURES

    public static function NotDeleted($status_code = 422,$message = self::STATUS_MESSAGES['cant_delete']) : ResponseType
    {
        return JsonResponse::createResponse(self::FAILURE,$status_code,null,self::tMessage($message));
    }

    public static function NotUpdated($status_code = 422,$message = self::STATUS_MESSAGES['cant_update']) : ResponseType
    {
        return JsonResponse::createResponse(self::FAILURE,$status_code,null,self::tMessage($message));
    }

    public static function UnknownError(): ResponseType
    {
        return JsonResponse::createResponse(self::FAILURE,422,null,self::tMessage('unknown_error'));
    }

    public static function Failure($status_code = 409,$message = ''): ResponseType
    {
        return JsonResponse::createResponse(self::FAILURE,$status_code,null,__($message));
    }

    public static function NotCreated($status_code = 422,$message = "Resource hasn't been created"): ResponseType
    {
        return JsonResponse::createResponse(self::FAILURE,$status_code,null,__($message));
    }

    public static function NoAccess($status_code = 403,$message = self::STATUS_MESSAGES['access_denied']): ResponseType
    {
        return JsonResponse::createResponse(self::FAILURE,$status_code,null,__($message));
    }

    public static function NoResult($status_code = 200,$message = self::STATUS_MESSAGES['no_matches']): ResponseType
    {
        return JsonResponse::createResponse(self::SUCCESS,$status_code,null,__($message));
    }


}
