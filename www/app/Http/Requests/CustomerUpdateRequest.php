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
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $fillable = [
            'email' => 'email',
            'password' => 'string',
			'enterprise_name' => 'string',
			'cpf' => 'string',
            'nome' => 'string'
        ];
        
        return $fillable;
    }

    public function messages()
	{
		return [
			'email.required' => 'Este campo não pode ser vazio',
			'email.unique' => 'O e-mail inserido já está em uso',
			'password.required' => 'A senha é obrigatória',
			'enterprise_name.required' => 'O nome da empresa é obrigatório',
			'cpf.required' => 'O CPF é obrigatório',
			'nome.required' => 'O nome é obrigatório'
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
