<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyRequest extends FormRequest {

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'valuteID' => 'required|alpha_dash',
            'numCode' => 'required|numeric',
            'nominal' => 'required',
            'сharCode' => 'required|alpha_dash',
            'name' => 'required',
            'value' => 'required|numeric',
            'date' => 'required|date_format:Y-m-d',
        ];
    }
}
