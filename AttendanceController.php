<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(private AttendanceService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Attendance::class);

        $filters = $request->validate([
            'employee_id' => ['nullable', 'exists:employees,id'],
            'date'        => ['nullable', 'date'],
            'from'        => ['nullable', 'date'],
            'to'          => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        // Absentees are shown for a single day: the chosen date, or today
        $absentDate = $filters['date'] ?? today()->toDateString();

        return view('admin.attendance.index', [
            'attendances' => $this->service->paginate($filters),
            'totalHours'  => $this->service->totalHours($filters),
            'absentees'   => $this->service->absentees($absentDate),
            'absentDate'  => $absentDate,
            'employees'   => Employee::with('user')->get()->sortBy('user.name'),
        ]);
    }
}