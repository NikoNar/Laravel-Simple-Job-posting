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
        User::chunk(200,function($users){
            foreach ($users as $user){
                $user->coins += COINS;
                $user->save();
            }
        });
        return 0;
    }
}