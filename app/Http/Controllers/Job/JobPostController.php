<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Job\JobPostRepository;
use App\Http\Requests\Job\StoreJobVacancy;
use Illuminate\Http\Request;

class JobPostController extends Controller
{
    private $job_post_repository;

    public function __construct()
    {
        $this->job_post_repository = new JobPostRepository();
    }

    public function store(StoreJobVacancy $request)
    {
        $this->job_post_repository->checkStoreCondition();
        $this->job_post_repository->storeJob($request);
        return $this->job_post_repository->response;
    }
}
