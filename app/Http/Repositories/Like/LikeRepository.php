<?php

namespace App\Http\Repositories\Like;

use App\Helpers\JsonResponse;
use App\Http\Repositories\Repository;
use App\Models\Job\JobPost;
use App\Models\Like\Like;
use App\Models\User;

class LikeRepository extends Repository
{
    private bool $like;

    public function __construct()
    {
        $this->like = true;
    }

    public function likeUser($user_to_like)
    {
        $this->user = User::getUser();
        $liked_before = Like::where([
            'likeable_type' => User::class,
            'likeable_id' => $user_to_like->id,
            'liked_by' => $this->user->id
        ])->first();

        if(!empty($liked_before)){

            $liked_before->delete();
            $this->response = JsonResponse::createResponse(
                JsonResponse::SUCCESS,
                200,
                null,
                'Disliked'
            );

        }else{

            Like::create([
                'likeable_type' => User::class,
                'likeable_id' => $user_to_like->id,
                'liked_by' => $this->user->id
            ]);

            $this->response = JsonResponse::createResponse(
                JsonResponse::SUCCESS,
                200,
                null,
                'Liked'
            );

        }

    }

    public function likeJobPost($jobPost)
    {
        $this->user = User::getUser();

        $liked_before = Like::where([
            'likeable_type' => JobPost::class,
            'likeable_id' => $jobPost->id,
            'liked_by' => $this->user->id
        ])->first();

        if(!empty($liked_before)){

            $liked_before->delete();
            $this->response = JsonResponse::createResponse(
                JsonResponse::SUCCESS,
                200,
                null,
                'Disliked'
            );

        }else{

            Like::create([
                'likeable_type' => JobPost::class,
                'likeable_id' => $jobPost->id,
                'liked_by' => $this->user->id
            ]);

            $this->response = JsonResponse::createResponse(
                JsonResponse::SUCCESS,
                200,
                null,
                'Liked'
            );

        }

    }
}
