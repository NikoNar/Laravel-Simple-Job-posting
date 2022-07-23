<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SendCoins extends Command
{
     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:coin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending coin to user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        define('COINS',(int)env('DAILY_COINS_AMOUNT'));
        define('MAX_COINS',(int)env('MAX_COINS_CAN_HAVE'));
        User::chunk(200,function($users){
            foreach ($users as $user){
                if(($user->coins + COINS) >= 5){
                    $user->coins = MAX_COINS;
                }else{
                    $user->coins += COINS;
                }

                $user->save();

            }
        });
        return 0;
    }
}
