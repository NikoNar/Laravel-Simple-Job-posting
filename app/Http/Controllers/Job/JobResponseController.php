<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Job\JobResponseRepository;
use App\Http\Requests\Job\StoreJobResponse;
use App\Models\Job\JobResponse;
use Illuminate\Http\Request;

class JobResponseController extends Controller
{
    private Object $job_response_repository;

    public function __construct()
    {
        $this->job_response_repository = new JobResponseRepository();
    }

    public function store(StoreJobResponse $request)
    {
        $this->job_response_repository->checkStoreResponseCondition($request);
        $this->job_response_repository->storeJobResponse($request);
        return $this->job_response_repository->response;
    }

    public function delete(JobResponse $response)
    {
        $this->job_response_repository->checkDeleteCondition($response);
        $this->job_response_repository->delete($response);
        return $this->job_response_repository->response;
    }
}
