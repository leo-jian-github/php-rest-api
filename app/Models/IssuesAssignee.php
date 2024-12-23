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

    public function issues()
    {
        return $this->belongsTo(related: Issue::class, foreignKey: 'issues_id', ownerKey: 'id');
    }

    public function user()
    {
        return $this->belongsTo(related: User::class, foreignKey: 'user_no', ownerKey: 'no');
    }

}
