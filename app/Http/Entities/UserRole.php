<?php

namespace App\Http\Entities;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{

    protected $table = 'user_role';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'roleName', 'slugRole', 'created_at', 'updated_at'
    ];
}