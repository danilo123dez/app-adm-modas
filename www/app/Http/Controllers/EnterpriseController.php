<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnterpriseUpdateRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EnterpriseController extends Controller
{
    public function index($customer_uuid){
        $customer = Customer::where('uuid', $customer_uuid)->first();
        $empresa = $customer->Enterprise()->first();
        return response([
            'error' => 0,
            'code' => 'enterprise',
            'data' => [
                'nome_empresa' => $empresa->nome,
                'email_empresa' => $empresa->email_empresa
            ]
        ],200);
    }

    public function update($customer_uuid, EnterpriseUpdateRequest $request){
        try{
            $customer = Customer::where('uuid', $customer_uuid)->first();
            $empresa = $customer->Enterprise()->first();
            $empresa->update($request->validated());
            return response([
                'error' => 0,
                'code' => 'enterprise_updated',
                'data' => [
                    'nome_empresa' => $empresa->nome,
                    'email_empresa' => $empresa->email_empresa
                ]
            ],200);
        }catch(\Exception $e){
            Log::error('[Error in update Enterpise]', [$e->getMessage(), $e->getFile(), $e->getLine()]);
            return [
                'error' => 1,
                'code' => 'invalid_request',
                'description' => 'Houve um erro inesperado'
            ];
        }
    }
}
