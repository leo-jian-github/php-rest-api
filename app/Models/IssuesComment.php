<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IssuesComment extends Model
{
    protected $fillable = [
        'issues_id',
        'user_no',
        'content',
        'created_at',
        'updated_at',
    ];
}
