<?php

namespace App\Http\Repositories\Job;

use App\Helpers\GlobalMethods;
use App\Helpers\JsonResponse;
use App\Http\Repositories\Repository;
use App\Models\Job\JobPost;
use App\Models\User;
use Illuminate\Support\Carbon;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class JobPostRepository extends Repository
{
    public function checkStoreCondition()
    {
        $this->user = User::getUser();

        if($this->user->getCoinsAmount() < 2){
            return $this->response = JsonResponse::Failure(
                422,
                "You don't have enough coins to post a job vacancy."
            );
        }

        $job_posts = JobPost::where([
            ['created_by',$this->user->id],
            ['created_at', '>=', Carbon::now()->subDay()->toDateTimeString()]
        ])->count();

        if($job_posts >= 2){
            return $this->response = JsonResponse::Failure(
                409,
                "You can't post a job vacancy more than two times in 24 hours"
            );
        }

    }

    public function storeJob($request)
    {
        if($this->failure()) {
            return ;
        }

        $fields_to_store = $request->validated();
        $fields_to_store['created_by'] = $this->user->id;

        if($jobPost = JobPost::create($fields_to_store)){

            $amount = $this->user->getCoinsAmount();
            $this->user->setCoinsAmount($amount-=2);
            $this->response = JsonResponse::Created($jobPost);

        }
        else{
            $this->response = JsonResponse::NotCreated();
        }

    }

    public function fetchList($request)
    {
        $job_posts = new JobPost();

        $filterByDate = function ($request,&$job_posts){
            $from = null;
            if($request->creation_date == "day"){
                $from = Carbon::now()->subDay();
            }elseif($request->creation_date == "week"){
                $from = Carbon::now()->subWeek();
            }else{
                $from = Carbon::now()->subMonth();
            }

            $job_posts = $job_posts->whereBetween('created_at', [
                $from, Carbon::now()
            ]);

        };

        if(
            isset($request->responses_count)
            && in_array($request->responses_count,["ASC","DESC"])
        ){
            $job_posts =  $job_posts->withCount('responses');
            if(
                isset($request->creation_date)
                && in_array($request->creation_date,["day","week","month"])
              ){
                $filterByDate($request,$job_posts);
            }

            $job_posts = $job_posts->orderBy('responses_count', $request->responses_count);
        }elseif(
            isset($request->creation_date)
            && in_array($request->creation_date,["day","week","month"])
        ){
            $filterByDate($request,$job_posts);
        }

        dd($job_posts->get());



        $this->response = JsonResponse::Fetched(
            $job_posts->paginate(100)
        );
    }

    public function checkUpdateCondition($request,JobPost $post)
    {
        $this->user = User::getUser();
        if(!$post->doesUserCreator($this->user->id)){
            return $this->response = JsonResponse::NoAccess();
        }
    }

    public function updateJobPost($request,JobPost $post)
    {
        if($this->failure())
            return ;

        $post->update($request->validated());
        $this->response = JsonResponse::Updated($post);
    }

    public function checkDeleteCondition(JobPost $post)
    {
        $this->user = User::getUser();
        if(!$post->doesUserCreator($this->user->id)){
            return $this->response = JsonResponse::NoAccess();
        }
    }

    public function deleteJobPost($post)
    {
        if($this->failure())
            return ;

        if($post->delete()){
            $this->response = JsonResponse::Deleted();
        }else{
            $this->response = JsonResponse::NotDeleted();
        }

    }

    public function checkForceDeleteCondition($post_id)
    {
        $this->is_valid_uuid($post_id);
        if($this->failure())
            return ;

        if(empty(JobPost::withTrashed()->where('id',$post_id)->first())){
            $this->response = JsonResponse::NoResult();
        }
    }

}
