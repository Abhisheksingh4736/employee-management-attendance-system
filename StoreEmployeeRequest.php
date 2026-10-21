<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        // return $this->user()->can('create', Employee::class);
        return $this->user()?->role === 'admin';
    }

   
public function rules(): array
{
    return [
        'employee_code' => 'required|string|max:50|unique:employees,employee_code',
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:employees,email|unique:users,email',
        'mobile' => 'nullable|string|max:20',
        'department_id' => 'nullable|exists:departments,id',
        'designation' => 'required|string|max:255',
        'joining_date' => 'required|date|before_or_equal:today',
        'is_active' => 'sometimes|boolean',
    ];
}

}

