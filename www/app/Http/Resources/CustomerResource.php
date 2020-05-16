<?php

namespace App\Http\Resources;

use App\Models\Customer;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $enterprise = Customer::where('uuid', $this->uuid)->first()->Enterprise()->select('nome')->first()->nome;
        return [
            'uuid' => $this->uuid,
            'email' => $this->email,
            'cpf' => $this->cpf,
            'nome' => $this->nome,
            'nome_empresa' => $enterprise
        ];
    }
}
