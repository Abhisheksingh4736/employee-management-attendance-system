<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use App\Models\Employee;

class Attendance extends Model
{
    protected $fillable = ['employee_id', 'date', 'check_in', 'check_out'];

    protected function casts(): array
    {
        return [
            'date'      => 'date',
            'check_in'           => 'datetime',
            'check_out'          => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    // Hours as decimal, null until check-out
    public function getWorkingHoursAttribute(): ?float
    {
        if (! $this->check_out) {
            return null;
        }

        return round($this->check_in->diffInMinutes($this->check_out, true) / 60, 2);
    }
}