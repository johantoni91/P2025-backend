<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    protected $table = 'access';
    protected $guarded = ['id'];

    public function module()
    {
        return $this->belongsTo(Modul::class, 'modules_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'roles_id', 'id');
    }
}
