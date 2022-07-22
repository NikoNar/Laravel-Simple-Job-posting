<?php

namespace App\Http\Repositories\User;
use App\Helpers\JsonResponse;
use App\Http\Repositories\Repository;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserRepository extends Repository
{
    public function fillStoreRequest(&$request): void
    {
        $request['password'] = Hash::make($request->password);
    }

    public function createUserAccount($request): void
    {
        $request->replace($request->except(['status']));

        if($user = User::create($request->all())) {
            $token = $user->createToken(Str::random(60))->plainTextToken;
            $this->response = JsonResponse::createResponse(JsonResponse::SUCCESS,201,$user,JsonResponse::STATUS_MESSAGES['registered'],
                ['token' => $token]
            );
        }else{
            $this->response = JsonResponse::NotCreated();
        }
    }

    public function checkLoginConditions($request)
    {
        $user = User::where('email',$request->email)
                    ->first();

        if(empty($user))
            $this->response = JsonResponse::createResponse(JsonResponse::FAILURE,404,null,JsonResponse::STATUS_MESSAGES['no_matches']);

        if(!empty($user))
            $this->user = $user;

        if(!empty($this->user)  && !Hash::check($request->password, $this->user->password))
            $this->response = JsonResponse::createResponse(JsonResponse::FAILURE,422,null,JsonResponse::STATUS_MESSAGES['incorrect_password']);
    }

    public function login()
    {
        if(!$this->failure() && $this->user != null)
            $this->response = JsonResponse::createResponse(
                JsonResponse::SUCCESS,
                200,
                $this->user,
                JsonResponse::STATUS_MESSAGES['authorized'],
                [
                    'token' => $this->user->createToken(Str::random(60))->plainTextToken
                ]

            );

    }

}
