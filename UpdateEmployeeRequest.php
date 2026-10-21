<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('employee'));
    }

    
public function rules(): array
{
    return [
        'employee_code' => 'required|string|max:50|unique:employees,employee_code,' . $this->route('employee')->id,
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:employees,email,' . $this->route('employee')->id,
        'mobile' => 'nullable|string|max:20',
        'department_id' => 'nullable|exists:departments,id',
        'designation' => 'required|string|max:255',
        'joining_date' => 'required|date',
        'is_active' => 'nullable|boolean',
    ];
}

}