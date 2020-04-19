<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Stores;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\LancamentoStoreRequest;
use App\Http\Requests\LancamentoUpdateRequest;

class LancamentosController extends Controller
{
    public function store($customer_uuid, $store_uuid, LancamentoStoreRequest $request){
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
            $loja = $empresa->Stores()->where('uuid', $store_uuid)->first();

            if(empty($loja)){
                return [
                    'error' => 1,
                    'code' => 'store_not_found',
                    'description' => 'Loja não listado na base de dados'
                ];
            }

            $inputs_validated = $request->validated();
            $inputs_validated['loja_id'] = $loja->id;
            $inputs_validated['data_compra'] = Carbon::parse($inputs_validated['data_compra'])->format('Y/m/d');
            $inputs_validated['data_vencimento'] = Carbon::parse($inputs_validated['data_vencimento'])->format('Y/m/d');

            $loja->Releases()->create($inputs_validated);

            return [
                'error' => 0,
                'code' => 'release_stored',
                'description' => 'Lançamento cadastrado com sucesso'
            ];

        }catch(\Exception $e){
            Log::error('[Store Releases]', [$e->getMessage(), [$e->getLine(), $e->getFile()]]);
            return [
                'error' => 1,
                'code' => 'invalid_request',
                'description' => 'Ocorreu um erro inesperado'
            ];
        }
    }

    public function update($customer_uuid, $store_uuid, $releases_uuid, LancamentoUpdateRequest $request){
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
            $loja = $empresa->Stores()->where('uuid', $store_uuid)->first();

            if(empty($loja)){
                return [
                    'error' => 1,
                    'code' => 'store_not_found',
                    'description' => 'Loja não listado na base de dados'
                ];
            }

            $lancamento = $loja->Releases()->where('uuid', $releases_uuid)->first();

            if(empty($lancamento)){
                return [
                    'error' => 1,
                    'code' => 'release_not_found',
                    'description' => 'Lançamento não listado na base de dados'
                ];
            }
            
            $inputs_validated = $request->validated();

            if(!empty($inputs_validated['data_compra'])){
                $inputs_validated['data_compra'] = Carbon::parse($inputs_validated['data_compra'])->format('Y/m/d');
                $inputs_validated['data_compra'] = Carbon::parse($inputs_validated['data_compra']);
            }
            if(!empty($inputs_validated['data_vencimento'])){
                $inputs_validated['data_vencimento'] = Carbon::parse($inputs_validated['data_vencimento'])->format('Y/m/d');
                $inputs_validated['data_vencimento'] = Carbon::parse($inputs_validated['data_vencimento']);
            }
            
            
            $lancamento->update($inputs_validated);
            return [
                'error' => 0,
                'code' => 'release_updated',
                'description' => 'Lançamento atualizado com sucesso'
            ];

        }catch(\Exception $e){
            Log::error('[Update Releases]', [$e->getMessage(), [$e->getLine(), $e->getFile()]]);
            return [
                'error' => 1,
                'code' => 'invalid_request',
                'description' => 'Ocorreu um erro inesperado'
            ]; 
        }
    }

    public function delete($customer_uuid, $store_uuid, $releases_uuid){
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
            $loja = $empresa->Stores()->where('uuid', $store_uuid)->first();

            if(empty($loja)){
                return [
                    'error' => 1,
                    'code' => 'store_not_found',
                    'description' => 'Loja não listado na base de dados'
                ];
            }

            $lancamento = $loja->Releases()->where('uuid', $releases_uuid)->first();

            if(empty($lancamento)){
                return [
                    'error' => 1,
                    'code' => 'release_not_found',
                    'description' => 'Lançamento não listado na base de dados'
                ];
            }
            
            $lancamento->delete();
            return [
                'error' => 0,
                'code' => 'release_deleted',
                'description' => 'Lançamento atualizado com sucesso'
            ];

        }catch(\Exception $e){
            Log::error('[Delete Releases]', [$e->getMessage(), [$e->getLine(), $e->getFile()]]);
            return [
                'error' => 1,
                'code' => 'invalid_request',
                'description' => 'Ocorreu um erro inesperado'
            ]; 
        }
    }
}
