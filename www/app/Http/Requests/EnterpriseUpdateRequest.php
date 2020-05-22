<?php

namespace App\Http\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;;

class EnterpriseUpdateRequest extends FormRequest
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
            'email_empresa' => 'email',
        ];

        return $fillable;
    }

    public function messages()
	{
		return [
			'email_empresa.email' => 'Digite um e-mail válido',
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
