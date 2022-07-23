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
}
