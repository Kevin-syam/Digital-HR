<?php

namespace App\Requests\OfficeTime;

use App\Models\OfficeTime;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OfficeTimeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_id' => 'required|exists:companies,id',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
            'description' => ['nullable', 'string', 'max:500'],
            'shift' => ['required',Rule::in(OfficeTime::SHIFT)],
            'category' => ['required',Rule::in(OfficeTime::CATEGORY)],
            'holiday_count' => 'nullable|numeric|gte:0',
            'is_active' => ['nullable', 'boolean', Rule::in([1, 0])],
        ];

    }

}












