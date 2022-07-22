<?php

namespace App\Models;

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
        'created_by'
    ];
}
