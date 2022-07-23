<?php

namespace App\Http\Controllers\Job;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Job\JobPostRepository;
use App\Http\Requests\Job\StoreJobVacancy;
use App\Http\Requests\Job\UpdateJobPost;
use App\Models\Job\JobPost;
use Illuminate\Http\Request;

class JobPostController extends Controller
{
    private $job_post_repository;

    public function __construct()
    {
        $this->job_post_repository = new JobPostRepository();
    }


    public function index(Request $request)
    {
        $this->job_post_repository->fetchList($request);
        return $this->job_post_repository->response;
    }

    public function store(StoreJobVacancy $request)
    {
        $this->job_post_repository->checkStoreCondition();
        $this->job_post_repository->storeJob($request);
        return $this->job_post_repository->response;
    }

    public function show(JobPost $id)
    {
        return JsonResponse::Fetched($id);
    }

    public function update(UpdateJobPost $request,JobPost $post)
    {
        $this->job_post_repository->checkUpdateCondition($request,$post);
        $this->job_post_repository->updateJobPost($request,$post);
        return $this->job_post_repository->response;
    }

    public function delete(JobPost $post)
    {
        $this->job_post_repository->checkDeleteCondition($post);
        $this->job_post_repository->deleteJobPost($post);
        return $this->job_post_repository->response;
    }

    public function forceDelete($post_id)
    {
        $this->job_post_repository->checkForceDeleteCondition($post_id);
        $this->job_post_repository->forceDelete(JobPost::class,$post_id);
        return $this->job_post_repository->response;
    }

}
