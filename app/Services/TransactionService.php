<?php

namespace App\Services;

use App\Models\Job\JobPost;
use App\Models\User;

class TransactionService
{

    public function get_rest_coins($user,$service_type)
    {
        if($service_type == "vacancy"){
            $user->coins -= 2;
            return $user->coins;
        }
    }

    public function set_coins_amount(&$user,$amount)
    {
        $max_coins = (int)env('MAX_COINS_CAN_HAVE');
        if($amount >= $max_coins){
            $user->coins = $max_coins;
        }else{
            $user->coins = $amount;
        }
        $user->save();
    }

    public function add_coins(&$user,$amount)
    {
        $max_coins = (int)env('MAX_COINS_CAN_HAVE');
        if(($user->coins + $amount) >= $max_coins){
            $user->coins = $max_coins;
        }else{
            $user->coins += $amount;
        }
        $user->save();
    }

    public function get_daily_coins(&$user)
    {
        $coins = (int)env('DAILY_COINS_AMOUNT');
        $max_coins = (int)env('MAX_COINS_CAN_HAVE');
        if(($user->coins + $coins) >= $max_coins){
            $user->coins = $max_coins;
        }else{
            $user->coins += $coins;
        }
        $user->save();
    }

    public function get_coins($user)
    {
        return $user->coins;
    }

    public function create_job_post($user)
    {
        if($user->coins < 2)
        {
            return false;
        }
        JobPost::create([
            'title' => 'TEST',
            'description' => 'TEST',
            'created_by' => $user->id
        ]);
        $user->coins-=2;
        $user->save();
        return true;
    }
}
