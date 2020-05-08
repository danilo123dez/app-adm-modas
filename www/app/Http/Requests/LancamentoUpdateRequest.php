<?php

namespace App\Http\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class LancamentoUpdateRequest extends FormRequest
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
            'boleta' => 'string',
            'romaneio' => 'string',
            'cliente' => 'string',
            'data_compra' => 'date_format:d/m/Y',
            'data_vencimento' => 'date_format:d/m/Y',
            'valor' => 'between:0.01,500.000',
            'loja' => 'string'
        ];
    }

    public function messages()
	{
		return [
            'data_compra.date_format' => 'É necessário enviar a data da compra em formato d/m/Y',
            'data_vencimento.date_format' => 'É necessário enviar a data de vencimento em formato d/m/Y'
		];
    }

    protected function failedValidation(Validator $validator)
	{
		$errors = (new ValidationException($validator))->errors();
		$errors = implode(". " , array_map(function ($arr) {
			return implode(". " , $arr);
		}, $errors));
		throw new HttpResponseException(
			response()->json(['error' => 1, 'code' => 'invalid_request', 'description' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
		);
    }
}
