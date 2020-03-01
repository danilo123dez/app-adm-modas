<?php

namespace App\Http\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class LojasStoreRequest extends FormRequest
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
            'nome' => 'required|string',
            'comissao' => 'required|numeric',
        ];
    }

    public function messages()
	{
		return [
			'nome.required' => 'O nome da loja é obrigatório',
			'comissao.numeric' => 'A comissão da loja precisa ser um número',
			'comissao.required' => 'É necessário enviar a comissão da Loja',
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
