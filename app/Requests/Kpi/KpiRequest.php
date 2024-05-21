<?php

namespace App\Requests\Kpi;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KpiRequest extends FormRequest
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
            'kpi_desc' => 'required|array',
            'unit' => 'required|array',
            'dept_id' => 'required|array',
            'weight' => 'required|array',
            'kpi_target' => 'required|array',
            'is_max' => ['nullable', 'array', Rule::in([1, 0])],
        ];
    }

}












