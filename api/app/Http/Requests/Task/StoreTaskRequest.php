<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:6',
            'description' => 'string|min:6',
            'due_date' => 'required|date_format:Y-m-d H:i:s|after_or_equal:' . date(DATE_ATOM),
            'status_id' => 'required|integer|exists:status,id',
            'selected_users' => 'required|array',
            'selected_users.*' => 'integer|exists:users,id',
            'start_date' => 'required|date_format:Y-m-d H:i:s|after_or_equal:' . date(DATE_ATOM),
            'end_date' => 'required|date_format:Y-m-d H:i:s|after_or_equal:' . date(DATE_ATOM),
            'remarks' => 'string|min:6',
        ];
    }
}
