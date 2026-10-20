<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            $employees = Employee::with('department')
                ->latest()
                ->paginate(10);

            return response()->json([
                'success' => true,
                'message' => 'Employees retrieved successfully.',
                'data' => $employees,
            ]);
        }

        $employee = Employee::with('department')
            ->where('user_id', $user->id)
            ->first();

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee profile not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Employee retrieved successfully.',
            'data' => $employee,
        ]);
    }

    public function show(Request $request, int $id)
    {
        $employee = Employee::with('department')->find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.',
            ], 404);
        }

        $user = $request->user();

        if (
            $user->role !== 'admin'
            && $employee->user_id !== $user->id
        ) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to view this employee.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Employee retrieved successfully.',
            'data' => $employee,
        ]);
    }
}