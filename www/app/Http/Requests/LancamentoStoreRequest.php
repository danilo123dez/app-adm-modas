<?php

namespace App\Http\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class LancamentoStoreRequest extends FormRequest
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
            'boleta' => 'required|string',
            'romaneio' => 'required|string',
            'cliente' => 'required|string',
            'data_compra' => 'required|date_format:d/m/Y',
            'data_vencimento' => 'required|date_format:d/m/Y',
            'valor' => 'required|between:0.01,500.000'
        ];
    }

    public function messages()
	{
		return [
			'boleta.required' => 'É necessário enviar o número da boleta',
            'romaneio.required' => 'É necessário enviar o número do romaneio',
            'cliente.required' => 'É necessário enviar o nome do cliente',
            'data_compra.required' => 'É necessário enviar a data da compra',
            'data_vencimento.required' => 'É necessário enviar a data de vencimento',
            'valor.required' => 'É necessário enviar o valor da compra'
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
