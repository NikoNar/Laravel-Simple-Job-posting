<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\TraitUuid;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,TraitUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'password',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getToken()
    {
        return request()->user()->currentAccessToken()->token;
    }

    public static function getUserIdByToken($token)
    {
        $user_token = DB::table('personal_access_tokens')
                        ->select('tokenable_id')
                        ->where([
                            ['tokenable_type',User::class],
                            ['token',$token]
                        ])
                        ->first();

        if(!empty($user_token->tokenable_id)) {
            return $user_token->tokenable_id;
        }

        return null;
    }

    public static function getUser(): User
    {
        $token = self::getToken();
        $user_id = self::getUserIdByToken($token);
        return self::find($user_id);
    }

    public static function getUserId()
    {
        return self::getUserIdByToken(self::getToken());
    }

    public static function getUserById($id)
    {
        return User::find($id);
    }

    public function getCoinsAmount()
    {
        return (int)$this->coins;
    }

    public function setCoinsAmount($new_amount)
    {
        $this->coins = $new_amount;
        $this->save();
    }

}
