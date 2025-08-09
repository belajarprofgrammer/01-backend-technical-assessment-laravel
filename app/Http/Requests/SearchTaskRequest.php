<?php

namespace App\Http\Requests;

use App\Enums\RecurringIntervalEnum;
use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchTaskRequest extends FormRequest
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
            'keyword' => [
                'nullable',
                'string',
                'max:255',
            ],
            'status' => [
                'nullable',
                'string',
                Rule::enum(StatusEnum::class),
            ],
            'is_recurring' => [
                'nullable',
                'boolean',
            ],
            'recurring_interval' => [
                'nullable',
                'string',
                Rule::enum(RecurringIntervalEnum::class),
            ],
            'due_date_from' => [
                'nullable',
                'date',
            ],
            'due_date_to' => [
                'nullable',
                'date',
            ],
            'page' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'per_page' => [
                'nullable',
                'integer',
                'min:1',
            ],
        ];
    }
}
