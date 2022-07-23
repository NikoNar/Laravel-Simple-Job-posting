<?php

namespace App\Models\Job;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobPost extends Model
{
    use TraitUuid,HasFactory,SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'created_by',
        ''
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'notified_at'
    ];

    public function responses()
    {
        return $this->hasMany(JobResponse::class,'post_id','id');
    }
}
