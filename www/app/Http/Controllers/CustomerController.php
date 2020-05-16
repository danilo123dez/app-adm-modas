<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Customer;
use App\Models\Enterprises;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Http\Resources\CustomerResource;
use Illuminate\Contracts\Session\Session;
use LaravelLegends\PtBrValidator\Validator;

class CustomerController extends Controller
{

    public function infoUserCustomer(Request $request){
        $user = $request->user();
        $customer = $user->loginable()->first();
        return response([
            'error' => 0,
            'code' => 'customer',
            'data' => new CustomerResource($customer)
        ],200);
    }

    public function store(CustomerStoreRequest $request){

        try{

            $enterprise = Enterprises::create([
                'nome' => $request['enterprise_name']
            ]);

            $customer = Customer::create([
                'cpf' => $request['cpf'],
                'email' => $request['email'],
                'nome' => $request['nome'],
                'empresa_id' => $enterprise->id
            ]);

            $user = User::create([
                'email' => $customer->email,
                'password' => $request['password'],
                'loginable_type' => Customer::class,
                'loginable_id' => $customer->id
            ]);
            return [
                'error' => 0,
                'code' => 'stored_customer',
                'description' => 'Cadastro feito com sucesso'
            ];
        }catch(Exception $e){
            DB::rollBack();
            Log::error('[Store Customer]', [$e->getMessage(), [$e->getLine(), $e->getFile()]]);
            return [
                'error' => 1,
                'code' => 'invalid_request',
                'description' => 'Ocorreu um erro inesperado'
            ];
        }
    }

    public function update($uuid, CustomerUpdateRequest $request) {

        try {
            $customer = Customer::where('uuid', $uuid)->first();
            $customerEmail = Customer::where('email', $request['email'])->where('email', '!=', $customer->email)->first();

            if(empty($customer)){
                return [
                    'error' => 1,
                    'code' => 'customer_not_found',
                    'description' => 'Usuário não listado na base de dados'
                ];
            }

            if (!empty($customerEmail)) {
                return [
                    'error' => 1,
                    'code' => 'email_already_exists',
                    'description' => 'E-mail já está em uso'
                ];
            }

            $customer->update($request->validated());

            if(!empty($request->nome_empresa)){
                $customer->Enterprise()->first()->update(['nome' => $request->nome_empresa]);
            }

            return [
                'error' => 0,
                'code' => 'updated_customer',
                'description' => 'Atualizado com sucesso',
                'data' => new CustomerResource($customer)
            ];

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('[Update Customer]', [$e->getMessage(), [$e->getLine(), $e->getFile()]]);
            return [
                'error' => 1,
                'code' => 'invalid_request',
                'description' => 'Ocorreu um erro inesperado'
            ];
        }
    }

    public function delete($uuid) {
        try {
            $customer = Customer::where('uuid', $uuid)->first();

            if(empty($customer)){
                return [
                    'error' => 1,
                    'code' => 'customer_not_found',
                    'description' => 'Usuário não listado na base de dados'
                ];
            }

            $user = $customer->User()->first();

            $customer->delete();
            $user->delete();

            return [
                'error' => 0,
                'code' => 'updated_customer',
                'description' => 'Deletado com sucesso'
            ];

        } catch (Exception $e) {
            Log::error('[Delete Customer]', [$e->getMessage(), [$e->getLine(), $e->getFile()]]);
            return [
                'error' => 1,
                'code' => 'invalid_request',
                'description' => 'Ocorreu um erro inesperado'
            ];
        }
    }

    public function admin($uuid){
        try {
            $customer = Customer::where('uuid', $uuid)->first();

            if(empty($customer)){
                return [
                    'error' => 1,
                    'code' => 'customer_not_found',
                    'description' => 'Usuário não listado na base de dados'
                ];
            }

            $empresa = $customer->Enterprise()->first();

            $customers = $empresa->Customer()->get();

            return [
                'error' => 0,
                'code' => 'admins_get',
                'description' => 'Lista de todos os admin',
                'data' => CustomerResource::collection($customers)
            ];

        } catch (Exception $e) {
            Log::error('[Get Admin\'s]', [$e->getMessage(), [$e->getLine(), $e->getFile()]]);
            return [
                'error' => 1,
                'code' => 'invalid_request',
                'description' => 'Ocorreu um erro inesperado'
            ];
        }
    }
}
