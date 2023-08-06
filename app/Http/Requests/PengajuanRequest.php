<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PengajuanRequest extends FormRequest
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
            'nomor_surat' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'nomor_surat.required' => 'Nomor Surat Tidak Boleh Kosong',
        ];
    }
}
