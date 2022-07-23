<?php

namespace App\Http\Repositories\Job;

use App\Helpers\JsonResponse;
use App\Http\Repositories\Repository;
use App\Models\Job\JobPost;
use App\Models\Job\JobResponse;
use App\Models\User;
use Carbon\Carbon;


class JobResponseRepository extends Repository
{
    private JobPost $job_post;

    public function __construct()
    {
        $this->job_post = new JobPost();
    }

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

        //uncomment
//        if($jobResponse->count() != 0){
//
//            return $this->response = JsonResponse::Failure(
//                409,
//                "You can't send response to this job second time"
//            );
//        }

        $this->job_post = $job_post;

    }

    public function storeJobResponse($request)
    {
        if($this->failure()) {
            return ;
        }

        $fields_to_store = $request->validated();
        $fields_to_store['sent_by'] = $this->user->id;

        if($job = JobResponse::create($fields_to_store)){

            $amount = $this->user->getCoinsAmount();
            $this->user->setCoinsAmount(--$amount);

            //sending notification
            $this->sendNotification($request,$job);
            $this->response = JsonResponse::Created($job);

        } else{
            $this->response = JsonResponse::NotCreated();
        }

    }

    private function sendNotification(&$request,&$job)
    {
        $count_of_responses = JobResponse::where('post_id',$request->post_id)
                                         ->count();

        //get post creator to send notification
        $delay = 0;
        $post_creator = User::find($this->job_post->created_by);
        $left_from = $this->job_post->notified_at->diffInMinutes();

        if($this->job_post->notified_at > Carbon::now()){
            $delay = $left_from += 60;
        }

        $this->job_post->notified_at = $this->job_post->notified_at->addMinutes(60)->toDateTimeString();
        $this->job_post->save();

        $post_creator->notify(
            new \App\Notifications\JobResponse(
                [
                    'job_vacancy' => $this->job_post,
                    'user' => $this->user,
                    'responses_count_to_post' => $count_of_responses,
                    'sent_date' => $job->created_at
                ],
                $delay
            )
        );

    }

    public function checkDeleteCondition($response)
    {
        $this->user = User::getUser();

        if(!$response->doesUserCreator($this->user->id)){
            $this->response = JsonResponse::NoAccess();
        }

    }

    public function delete($response)
    {
        if($this->failure()){
            return ;
        }
        $response->delete();
        $this->response = JsonResponse::Deleted();
    }

}
