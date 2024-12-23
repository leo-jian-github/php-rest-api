<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $fillable = [
        'title',
        'content',
        'user_no',
    ];

    public function assignees()
    {
        return $this->hasMany(related: IssuesAssignee::class, foreignKey: 'issues_id', localKey: 'id');
    }

    public function user()
    {
        return $this->belongsTo(related: User::class, foreignKey: 'user_no', ownerKey: 'no');
    }
}
