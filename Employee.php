<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Department;
use App\Models\Attendance;
use App\Enums\EmployeeStatus;

class Employee extends Model
{
     use HasFactory;
     
protected $fillable = [
    'user_id',
    'department_id',
    'employee_code',
    'mobile',
    'designation',
    'joining_date',
    'status',
];

    protected $casts = 
    [
    'joining_date' => 'date',
    'status'       => EmployeeStatus::class,
   ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}