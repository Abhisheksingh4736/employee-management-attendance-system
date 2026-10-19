<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EmployeeController extends Controller
{
    public function __construct(private EmployeeService $service) {}

     use AuthorizesRequests;

    public function index(Request $request)
    {
        // $this->authorize('viewAny', Employee::class);

        $employees   = $this->service->paginate($request->only(['search', 'status', 'department_id']));
        $departments = Department::orderBy('name')->get();

        return view('admin.employees.index', compact('employees', 'departments'));
    }

    // public function create()
    // {
    //     Employee::create([
    //     'user_id' => $user->id,
    //     'employee_code' => $validated['employee_code'],
    //     'name' => $validated['name'],
    //     'email' => $validated['email'],
    //     'mobile' => $validated['mobile'] ?? null,
    //     'department_id' => $validated['department_id'] ?? null,
    //     'designation' => $validated['designation'],
    //     'joining_date' => $validated['joining_date'],
    //     'is_active' => $validated['is_active'] ?? true,
    // ]);

    //     return view('admin.employees.create', ['departments' => Department::orderBy('name')->get()]);
        
    // }

    public function create()
    {
        return view('admin.employees.create', [
            'departments' => Department::orderBy('name')->get()
        ]);
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'employee_code' => 'required|string|max:50|unique:employees,employee_code',
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:employees,email',
        'mobile' => 'nullable|string|max:20',
        'department_id' => 'nullable|exists:departments,id',
        'designation' => 'required|string|max:255',
        'joining_date' => 'required|date',
        'is_active' => 'nullable|boolean',
    ]);

    $validated['is_active'] = $request->boolean('is_active');

    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => \Illuminate\Support\Facades\Hash::make(
            \Illuminate\Support\Str::random(32)
        ),
        'role' => 'employee',
    ]);

    $validated['user_id'] = $user->id;

    \App\Models\Employee::create($validated);

    return redirect()
        ->route('admin.employees.index')
        ->with('success', 'Employee created successfully!');
}

    public function show(Employee $employee)
    {
        $this->authorize('view', $employee);

        return view('admin.employees.show', ['employee' => $employee->load(['user', 'department'])]);
    }

    public function edit(Employee $employee)
    {
        $this->authorize('update', $employee);

        return view('admin.employees.edit', [
            'employee'    => $employee->load('user'),
            'departments' => Department::orderBy('name')->get(),
        ]);
    }

    
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $validated = $request->validated();

        // Checkbox unchecked hone par bhi false save hoga
        $validated['is_active'] = $request->boolean('is_active');

        $this->service->update($employee, $validated);

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Employee updated successfully.');
    }


    public function toggleStatus(Employee $employee)
    {
        $employee->update([
            'is_active' => ! $employee->is_active,
        ]);

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Employee status updated successfully!');
    }

        public function destroy(Employee $employee)
    {
        // Remove the related employee login account, if present
        if ($employee->user) {
            $employee->user->delete();
        }

        $employee->delete();

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Employee deleted successfully!');
    }
}