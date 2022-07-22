<?php

namespace App\Http\Repositories\User;
use App\Helpers\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserRepository
{
    public function fillStoreRequest(&$request): void
    {
        $request['password'] = Hash::make($request->password);
    }

    public function createUserAccount($request): void
    {
        $request->replace($request->except(['status']));

        if($user = User::create($request->all()))
        {
            $token = $user->createToken(Str::random(60))->plainTextToken;
            $this->response = JsonResponse::createResponse(JsonResponse::SUCCESS,201,$user,JsonResponse::STATUS_MESSAGES['registered'],
                ['token' => $token]
            );
        }else
            $this->response = JsonResponse::NotCreated();

    }
}
