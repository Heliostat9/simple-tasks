<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleEmployee extends Model
{
    protected $table = 'role_employees';

    protected $fillable = [
        'role_id',
        'employee_id',
    ];
}
