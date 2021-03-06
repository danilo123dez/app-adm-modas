<?php

namespace App\Http\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomerStoreRequest extends FormRequest
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
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'numero' => 'required|min:11',
			'cpf' => 'required|min:11|unique:customers',
            'nome' => 'required',
            'customer_uuid' => 'required'
		];

		return $fillable;

	}

	public function messages()
	{
		return [
			'email.required' => 'O e-mail é obrigatório',
			'email.unique' => 'O e-mail inserido já está em uso',
			'password.required' => 'A senha é obrigatória',
			'cpf.required' => 'O CPF é obrigatório',
            'nome.required' => 'O nome é obrigatório',
            'cpf.min' => 'O CPF precisa ter 11 caracteres',
            'cpf.unique' => 'Este CPF já está sendo usado',
            'customer_uuid.required' => 'Houve um erro inesperado, atualize a página e tente novamente!',
            'numero.required' => "É necessário enviar um telefone",
            "numero.min" => "O telefone precisa ter no minimo 11 digitos"
		];
	}


	protected function failedValidation(Validator $validator)
	{
		$errors = (new ValidationException($validator))->errors();
		$errors = str_replace("\n", ". \n", implode("\n" , array_map(function ($arr) {
			return implode("\n" , $arr);
		}, $errors)));
		throw new HttpResponseException(
			response()->json(['error' => 1, 'code' => 'invalid_request', 'description' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
		);
    }

}
