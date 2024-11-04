<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $guarded = [
        'id'
    ];

    protected $hidden = [
        'password',
    ];

    public function Role()
    {
        return $this->belongsTo(Role::class, 'role', 'id');
    }
}
