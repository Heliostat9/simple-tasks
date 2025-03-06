<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskSortRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sort_by' => 'sometimes|string|in:id,name,status,created_at,updated_at,updated_at',
            'sort_order' => 'sometimes|string|in:asc,desc',
        ];
    }
}
