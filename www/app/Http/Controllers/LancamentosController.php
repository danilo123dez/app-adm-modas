<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Stores;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\LancamentoStoreRequest;
use App\Http\Requests\LancamentoUpdateRequest;
use App\Http\Resources\ReleasesResource;
use App\Models\Releases;

class LancamentosController extends Controller
{

    public function index($customer_uuid){
        $customer = Customer::where('uuid', $customer_uuid)->first();

        if (empty($customer)) {
            return response([
                'error' => 1,
                'code' => 'customer_not_found',
                'description' => 'Usuário não listado na base de dados'
            ], 404);
        }

        $empresa = $customer->Enterprise()->first();
        
        $releases = $empresa->Releases()->select('releases.uuid', 'releases.boleta', 'releases.romaneio', 'releases.cliente',
                    'releases.data_compra', 'releases.data_vencimento', 'releases.valor', 'stores.nome as nome_loja', 'stores.uuid as loja_uuid')->get();
        
        return [
            'error' => 0,
            'code' => 'releases',
            'data' => ReleasesResource::collection($releases) 
        ];

    }

    public function show($customer_uuid, $store_uuid, $releases_uuid){
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

        $release = $loja->Releases()->select('uuid', 'boleta', 'romaneio', 'cliente',
        'data_compra', 'data_vencimento', 'valor', 'loja_id')->where('uuid', $releases_uuid)->first();

        return response([
            'error' => 0,
            'code' => 'release',
            'data' => new ReleasesResource($release)
        ]);
    }

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
            $inputs_validated['data_compra'] = Carbon::parse( str_replace('/', '-', $inputs_validated['data_compra']) )->format('Y/m/d');
            $inputs_validated['data_vencimento'] = Carbon::parse( str_replace('/', '-', $inputs_validated['data_vencimento']) )->format('Y/m/d');

            $release_stored = $loja->Releases()->create($inputs_validated);

            return [
                'error' => 0,
                'code' => 'release_stored',
                'description' => 'Lançamento cadastrado com sucesso',
                'data' => $release_stored->uuid
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

            $inputs_validated = $request->validated();

            if(!empty($request->loja) && $request->loja !== $store_uuid){
                $lancamento = Releases::where('uuid', $releases_uuid)->first();
                $inputs_validated['loja_id'] = Stores::where('uuid', $inputs_validated['loja'])->first()->id;
                unset($inputs_validated['loja']);
            }else{
                $lancamento = $loja->Releases()->where('uuid', $releases_uuid)->first();
            }
            

            if(empty($lancamento)){
                return [
                    'error' => 1,
                    'code' => 'release_not_found',
                    'description' => 'Lançamento não listado na base de dados'
                ];
            }

            if(!empty($inputs_validated['data_compra'])){
                $inputs_validated['data_compra'] = Carbon::parse( Carbon::parse( str_replace('/', '-', $inputs_validated['data_compra']) )->format('Y/m/d') );
            }

            if(!empty($inputs_validated['data_vencimento'])){
                $inputs_validated['data_vencimento'] = Carbon::parse( Carbon::parse( str_replace('/', '-', $inputs_validated['data_vencimento']) )->format('Y/m/d') );
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
                'description' => 'Lançamento apagado com sucesso'
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
