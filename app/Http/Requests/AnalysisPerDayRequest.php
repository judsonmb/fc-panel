<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnalysisPerDayRequest extends FormRequest
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
            $end = date('Y-m-d');
            $endDay = date('w', strtotime($end));
            $daysToLastMonday = ($endDay != 0) ? (int) $endDay - 1 : 6;
            $start = date('Y-m-d', strtotime(date('Y-m-d') . " - $daysToLastMonday days"));
            $this->merge([
                'startDate' => $start,
                'endDate' => $end,
            ]);
        }else{
            if($this->all()['endDate'] > date('Y-m-d')){
                $this->merge([
                    'endDate' => date('Y-m-d'),
                ]);
            }
            elseif($this->all()['startDate'] > $this->all()['endDate']){
                $this->merge([
                    'endDate' => $this->all()['startDate'],
                ]);
            }
    
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
