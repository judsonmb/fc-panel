<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnalyzeRequest extends FormRequest
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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if(count($this->all()) <= 1)
        {
            $this->merge([
                'customer' => '%',
                'product' => '%',
                'status' => '%',
                'export' => 0,
                'startDate' => date('Y-m-d'),
                'endDate' => date('Y-m-d'),
            ]);
        }
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'startDate' => 'date',
            'endDate' => 'date|after_or_equal:startDate',
        ];
    }
}
