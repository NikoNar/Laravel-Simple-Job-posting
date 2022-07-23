<?php

namespace App\Models\Like;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory,TraitUuid;

    protected $fillable = [
        'likeable_id',
        'likeable_type',
        'liked_by'
    ];
}
