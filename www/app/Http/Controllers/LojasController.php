<?php

namespace App\Http\Controllers;

use App\Http\Requests\LojasStoreRequest;
use App\Http\Requests\StoresUpdateRequest;
use App\Http\Resources\ReleasesResource;
use App\Models\Customer;
use App\Models\Stores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LojasController extends Controller
{

    public function index($customer_uuid)
    {
        $customer = Customer::where('uuid', $customer_uuid)->first();

        if (empty($customer)) {
            return response([
                'error' => 1,
                'code' => 'customer_not_found',
                'description' => 'Usuário não listado na base de dados'
            ], 404);
        }

        $empresa = $customer->Enterprise()->first();
        $lojas = $empresa->Stores()->select('uuid', 'nome', 'comissao')->get();
        return response([
            'error' => 0,
            'code' => 'lojas_index',
            'data' => $lojas
        ], 200);
    }

    public function show($customer_uuid, $store_uuid)
    {
        $customer = Customer::where('uuid', $customer_uuid)->first();

        if (empty($customer)) {
            return response([
                'error' => 1,
                'code' => 'customer_not_found',
                'description' => 'Usuário não listado na base de dados'
            ], 404);
        }

        $empresa = $customer->Enterprise()->first();

        $loja = $empresa->Stores()->where('uuid', $store_uuid)->first();

        if (empty($loja)) {
            return response([
                'error' => 1,
                'code' => 'store_not_found',
                'description' => 'Loja não listada na base de dados'
            ],404);
        }

        $releases = $loja->Releases()->select('uuid', 'boleta', 'romaneio', 'cliente', 'data_compra', 'data_vencimento', 'valor')->get();
        
        return response([
            'error' => 0,
            'code' => 'lojas_show',
            'data' => ['loja' => $loja, 'lancamentos' => ReleasesResource::collection($releases) ]
        ]);

    }

    public function store($customer_uuid, LojasStoreRequest $request)
    {
        try {
            $customer = Customer::where('uuid', $customer_uuid)->first();

            if (empty($customer)) {
                return [
                    'error' => 1,
                    'code' => 'customer_not_found',
                    'description' => 'Usuário não listado na base de dados'
                ];
            }

            $empresa = $customer->Enterprise()->first();

            $loja = $empresa->Stores()->create($request->validated());
            return [
                'error' => 0,
                'code' => 'stored_store',
                'description' => 'Loja cadastrada com sucesso',
                'data' => $loja->uuid
            ];
        } catch (\Exception $e) {
            Log::error('[Store Stores]', [$e->getMessage(), [$e->getLine(), $e->getFile()]]);
            return [
                'error' => 1,
                'code' => 'invalid_request',
                'description' => 'Ocorreu um erro inesperado'
            ];
        }
    }

    public function update($customer_uuid, $store_uuid, StoresUpdateRequest $request)
    {
        try {
            $customer = Customer::where('uuid', $customer_uuid)->first();

            if (empty($customer)) {
                return [
                    'error' => 1,
                    'code' => 'customer_not_found',
                    'description' => 'Usuário não listado na base de dados'
                ];
            }

            $empresa = $customer->Enterprise()->first();
            $loja = $empresa->Stores()->where('uuid', $store_uuid)->first();

            if (empty($loja)) {
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
        } catch (\Exception $e) {
            Log::error('[Update Stores]', [$e->getMessage(), [$e->getLine(), $e->getFile()]]);
            return [
                'error' => 1,
                'code' => 'invalid_request',
                'description' => 'Ocorreu um erro inesperado'
            ];
        }
    }

    public function delete($customer_uuid, $store_uuid)
    {
        try {

            $customer = Customer::where('uuid', $customer_uuid)->first();

            if (empty($customer)) {
                return [
                    'error' => 1,
                    'code' => 'customer_not_found',
                    'description' => 'Usuário não listado na base de dados'
                ];
            }

            $empresa = $customer->Enterprise()->first();
            $loja = $empresa->Stores()->where('uuid', $store_uuid)->first();

            if (empty($loja)) {
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
        } catch (\Exception $e) {
            Log::error('[Delete Stores]', [$e->getMessage(), [$e->getLine(), $e->getFile()]]);
            return [
                'error' => 1,
                'code' => 'invalid_request',
                'description' => 'Ocorreu um erro inesperado'
            ];
        }
    }
}
