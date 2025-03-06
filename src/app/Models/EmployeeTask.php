<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeTask extends Model
{
    protected $table = 'employee_tasks';
    protected $fillable = [
        'employee_id',
        'task_id',
    ];
}
