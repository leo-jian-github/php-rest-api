<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IssuesAssignee extends Model
{
    protected $fillable = [
        'issues_id',
        'user_no',
        'created_at',
        'updated_at',
    ];

}
