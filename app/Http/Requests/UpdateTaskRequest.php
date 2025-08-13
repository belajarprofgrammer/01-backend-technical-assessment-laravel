<?php

namespace App\Http\Requests;

use App\Enums\RecurringIntervalEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
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
            'title' => [
                'required',
                'string',
                'min:3',
                'max:100',
            ],
            'description' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'due_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'is_recurring' => [
                'required',
                'boolean',
            ],
            'recurring_interval' => [
                'nullable',
                'required_if:is_recurring,true',
                'string',
                Rule::enum(RecurringIntervalEnum::class),
            ],
        ];
    }
}
