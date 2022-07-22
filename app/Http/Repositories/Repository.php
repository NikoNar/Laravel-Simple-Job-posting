<?php


namespace App\Http\Repositories;

use Illuminate\Http\JsonResponse;
use App\Helpers\JsonResponse as JResponse;
use Illuminate\Support\Str;
use App\Helpers\GlobalMethods;



class Repository
{
    public JsonResponse $response;
    public object $user;
    public object $project;
    public int $row_limit;
    public object $media;

    public function __construct()
    {
        $this->row_limit = env('DEFAULT_SHOW_PER_PAGE');
    }

    public function failure(): bool
    {
        if(!empty($this->response))
            return $this->response->getData()->status == JResponse::FAILURE;
        else
            return false;
    }

    public function destroy($model_class,$id)
    {
        if(!$this->failure()) {
            $model = $model_class::where('id',$id)->first();
            if(!empty($model)) {
                $model->delete();
                $this->response = JResponse::createResponse(JResponse::SUCCESS,200,null,JResponse::STATUS_MESSAGES['deleted']);
            }else{
                $this->response = JResponse::UnknownError();
            }

        }
    }

    public function forceDelete($model_class,$id)
    {
        if(!$this->failure()) {
            GlobalMethods::forceDelete($model_class,$id)
                ? $this->response = JResponse::createResponse(JResponse::SUCCESS,200,null,JResponse::STATUS_MESSAGES['deleted'])
                : $this->response = JResponse::UnknownError();
        }
    }

    public function restore($model_class,$id)
    {
        if(!$this->failure()) {
            GlobalMethods::restore($model_class,$id)
                ? $this->response = JResponse::createResponse(JResponse::SUCCESS,200,null,JResponse::STATUS_MESSAGES['restored'])
                : $this->response = JResponse::UnknownError();
        }
    }

    public function is_valid_uuid($uuid)
    {
        !Str::isUuid($uuid)
            ? $this->response = JResponse::Failure(400,JResponse::STATUS_MESSAGES['incorrect_uuid'])
            : false;
    }

}
