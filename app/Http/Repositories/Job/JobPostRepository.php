<?php

namespace App\Http\Repositories\Job;

use App\Helpers\JsonResponse;
use App\Http\Repositories\Repository;
use App\Models\Job\JobPost;
use App\Models\User;
use Illuminate\Support\Carbon;

class JobPostRepository extends Repository
{
    public function checkStoreCondition()
    {
        $this->user = User::getUser();
        $job_posts = JobPost::where([
            ['created_by',$this->user->id],
            ['created_at', '>=', Carbon::now()->subDay()->toDateTimeString()]
        ])->count();

        if($job_posts >= 2){
            $this->response = JsonResponse::Failure(
                409,
                "You can't post a job vacancy more than two times in 24 hours"
            );
        }


    }

    public function storeJob($request)
    {
        if(!$this->failure()){

            $fields_to_store = $request->validated();
            $fields_to_store['created_by'] = $this->user->id;
            if($jobPost = JobPost::create($fields_to_store))
                $this->response = JsonResponse::Created($jobPost);
            else
                $this->response = JsonResponse::NotCreated();

        }
    }
}
