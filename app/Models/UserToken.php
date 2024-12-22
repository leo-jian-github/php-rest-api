<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    protected $table = 'user_tokens';

    protected $fillable = [
        'token',
        'user_no',
    ];

    public function user()
    {
        return $this->belnogTo(User::class);
    }
}
