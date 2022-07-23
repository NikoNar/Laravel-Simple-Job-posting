<?php

namespace App\Http\Controllers\Like;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Like\LikeRepository;
use App\Http\Requests\Like\LikeUser;
use App\Models\Job\JobPost;
use App\Models\User;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    private Object $like_repository;

    public function __construct()
    {
        $this->like_repository = new LikeRepository();
    }

    public function likeUser(Request $request,User $user)
    {
        $this->like_repository->likeUser($user);
        return $this->like_repository->response;
    }

    public function likeJob(Request $request,JobPost $jobPost)
    {
        $this->like_repository->likeJobPost($jobPost);
        return $this->like_repository->response;
    }

    public function getCount($object_id)
    {
        $this->like_repository->checkGetCountCondition($object_id);
        $this->like_repository->getCount($object_id);
        return $this->like_repository->response;
    }
}
