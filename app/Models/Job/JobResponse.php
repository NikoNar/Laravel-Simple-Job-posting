<?php

namespace App\Models\Job;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobResponse extends Model
{
    use HasFactory,TraitUuid;

    protected $fillable = [
        'post_id',
        'sent_by',
        'description'
    ];

    public function doesUserCreator($user_id)
    {
        if($this->sent_by == $user_id){
            return true;
        }
        return false;
    }
}
