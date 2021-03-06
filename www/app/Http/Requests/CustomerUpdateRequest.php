<?php

namespace App\Http\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;;

class CustomerUpdateRequest extends FormRequest
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

        $fillable = [
            'nome' => 'string|min:1',
            'email' => 'email',
			'cpf' => 'string|min:11',
            'nome' => 'string|min:2',
            'numero' => 'string|min:11'
        ];

        return $fillable;
    }

    public function messages()
	{
		return [
            'email.email' => 'Digite um e-mail válido',
            "numero.min" => "O telefone precisa ter no minimo 11 digitos"
		];
    }

    protected function failedValidation(Validator $validator)
	{
		$errors = (new ValidationException($validator))->errors();
		$errors = implode("\n" , array_map(function ($arr) {
			return implode("\n" , $arr);
		}, $errors));
		throw new HttpResponseException(
			response()->json(['error' => 1, 'code' => 'invalid_request', 'description' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
		);
    }
}
