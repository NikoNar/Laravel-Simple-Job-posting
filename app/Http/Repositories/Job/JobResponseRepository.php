<?php

namespace App\Http\Repositories\Job;

use App\Helpers\JsonResponse;
use App\Http\Repositories\Repository;
use App\Models\Job\JobPost;
use App\Models\Job\JobResponse;
use App\Models\User;

class JobResponseRepository extends Repository
{
    public function checkStoreResponseCondition($request)
    {
        $this->user = User::getUser();
        $job_post = JobPost::find($request->post_id);
        $this->user->setCoinsAmount(4);

        if($this->user->getCoinsAmount() < 1){
            return $this->response = JsonResponse::Failure(
                422,
                "You don't have enough coins to send response to job vacancy."
            );
        }


        if($job_post->created_by == $this->user->id) {
            return $this->response = JsonResponse::Failure(
                409,
                "You can't send response to job posts created by you"
            );
        }


        $jobResponse = JobResponse::where([
            'post_id' => $request->post_id,
            'sent_by' => $this->user->id
        ]);

        if($jobResponse->count() != 0){
            return $this->response = JsonResponse::Failure(
                409,
                "You can't send response to this job second time"
            );
        }



    }

    public function storeJobResponse($request)
    {
        if(!$this->failure()) {
            return ;
        }

        $fields_to_store = $request->validated();
        $fields_to_store['sent_by'] = $this->user->id;

        if($job = JobResponse::create($fields_to_store)){

            $amount = $this->user->getCoinsAmount();
            $this->user->setCoinsAmount(--$amount);
            $this->response = JsonResponse::Created($job);

        } else{
            $this->response = JsonResponse::NotCreated();
        }


    }
}
