<?php

namespace App\Http\Repositories\Job;

use App\Helpers\JsonResponse;
use App\Http\Repositories\Repository;
use App\Models\JobPost;
use App\Models\User;

class JobPostRepository extends Repository
{
    public function storeJob($request)
    {
        $fields_to_store = $request->validated();
        $fields_to_store['created_by'] = User::getUserId();
        if($jobPost = JobPost::create($fields_to_store))
            $this->response = JsonResponse::Created($jobPost);
        else
            $this->response = JsonResponse::NotCreated();
    }
}
