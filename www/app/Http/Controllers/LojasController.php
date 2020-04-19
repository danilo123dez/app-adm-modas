<?php

namespace App\Http\Controllers;

use App\Http\Requests\LojasStoreRequest;
use App\Http\Requests\StoresUpdateRequest;
use App\Models\Customer;
use App\Models\Stores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LojasController extends Controller
{
    public function store($customer_uuid, LojasStoreRequest $request){
        try{
            $customer = Customer::where('uuid', $customer_uuid)->first();

            if(empty($customer)){
                return [
                    'error' => 1,
                    'code' => 'customer_not_found',
                    'description' => 'Usuário não listado na base de dados'
                ];
            }

            $empresa = $customer->Enterprise()->first();

            $empresa->Stores()->create($request->validated());
            return [
                'error' => 0,
                'code' => 'stored_store',
                'description' => 'Loja cadastrada com sucesso'
            ];
        }catch(\Exception $e){
            Log::error('[Store Stores]', [$e->getMessage(), [$e->getLine(), $e->getFile()]]);
            return [
                'error' => 1,
                'code' => 'invalid_request',
                'description' => 'Ocorreu um erro inesperado'
            ];
        }
    }

    public function update($store_uuid, StoresUpdateRequest $request){
        try{
            $loja = Stores::where('uuid', $store_uuid)->first();

            if(empty($loja)){
                return [
                    'error' => 1,
                    'code' => 'store_not_found',
                    'description' => 'Loja não listada na base de dados'
                ];
            }

            $loja->update($request->all());

            return [
                'error' => 0,
                'code' => 'updated_store',
                'description' => 'Loja atualizada com sucesso'
            ];

        }catch(\Exception $e){
            Log::error('[Update Stores]', [$e->getMessage(), [$e->getLine(), $e->getFile()]]);
            return [
                'error' => 1,
                'code' => 'invalid_request',
                'description' => 'Ocorreu um erro inesperado'
            ];
        }
    }

    public function delete($store_uuid){
        try{
            $loja = Stores::where('uuid', $store_uuid)->first();

            if(empty($loja)){
                return [
                    'error' => 1,
                    'code' => 'store_not_found',
                    'description' => 'Loja não listada na base de dados'
                ];
            }

            $loja->delete();

            return [
                'error' => 0,
                'code' => 'deleted_store',
                'description' => 'Loja apagada com sucesso'
            ];

        }catch(\Exception $e){
            Log::error('[Delete Stores]', [$e->getMessage(), [$e->getLine(), $e->getFile()]]);
            return [
                'error' => 1,
                'code' => 'invalid_request',
                'description' => 'Ocorreu um erro inesperado'
            ];
        }
    }

}
