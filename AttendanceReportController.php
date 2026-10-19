<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Enums\EmployeeStatus;
use App\Models\User;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

class AttendanceReportController  extends Controller
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

        // Absentees are shown for one day: the chosen date, or today
        $absentDate = $filters['date'] ?? today()->toDateString();

        return view('admin.attendance.index', [
            'attendances' => $this->service->paginate($filters),
            'totalHours'  => $this->service->totalHours($filters),
            'absentees'   => $this->service->absentees($absentDate),
            'absentDate'  => $absentDate,
            'employees'   => Employee::with('user')->get()->sortBy('user.name'),
        ]);
    }

    
   public function absent(Request $request)
{
    $this->authorize('viewAny', Attendance::class);

    $validated = $request->validate(['date' => ['nullable', 'date']]);
    $date = $validated['date'] ?? today()->toDateString();

    $employees = Employee::with(['user', 'department'])
        ->where('status', EmployeeStatus::Active)
        ->whereDoesntHave('attendances', fn ($q) => $q->whereDate('date', $date))
        ->orderBy(User::select('name')->whereColumn('users.id', 'employees.user_id'))
        ->paginate(10)
        ->withQueryString();

    return view('admin.attendance.absent', compact('employees', 'date'));
}

}
