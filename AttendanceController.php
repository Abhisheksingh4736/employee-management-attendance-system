<?php

namespace App\Http\Controllers\Employee;

use App\Exceptions\AttendanceException;
use App\Http\Controllers\Controller;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(private AttendanceService $service) {}

    public function index(Request $request)
    {
        $employee = $request->user()->employee;
        abort_if(! $employee, 403, 'No employee profile linked to this account.');

        return view('employee.attendance.index', [
            'today'       => $this->service->today($employee),
            'attendances' => $this->service->paginate([
                'employee_id' => $employee->id,   // forced, so nobody can see others' records
                'from'        => $request->input('from'),
                'to'          => $request->input('to'),
            ]),
        ]);
    }

    public function checkIn(Request $request)
    {
        return $this->perform($request, 'checkIn', 'Checked in successfully.');
    }

    public function checkOut(Request $request)
    {
        return $this->perform($request, 'checkOut', 'Checked out successfully.');
    }

    private function perform(Request $request, string $action, string $message)
    {
        $employee = $request->user()->employee;
        abort_if(! $employee, 403);

        try {
            $this->service->{$action}($employee);
        } catch (AttendanceException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', $message);
    }
}