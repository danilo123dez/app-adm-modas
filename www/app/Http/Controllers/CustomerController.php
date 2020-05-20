<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Customer;
use App\Models\Enterprises;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\CustomerResource;
use Illuminate\Contracts\Session\Session;
use App\Http\Requests\CustomerStoreRequest;
use LaravelLegends\PtBrValidator\Validator;
use App\Http\Requests\CustomerUpdateRequest;
use App\Mail\NovoAdmin;

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
        DB::beginTransaction();
        try{
            $customer_owner = Customer::where('uuid', $request->customer_uuid)->first();
            $enterprise = $customer_owner->Enterprise()->first();

            $customer = Customer::create([
                'cpf' => $request['cpf'],
                'email' => $request['email'],
                'nome' => $request['nome'],
                'numero' => $request['numero'],
                'empresa_id' => $enterprise->id
            ]);

            $user = User::create([
                'email' => $customer->email,
                'password' => $request['password'],
                'loginable_type' => Customer::class,
                'loginable_id' => $customer->id
            ]);

            $customer_email = [
                'nome' => $request['nome'],
                'email' => $request['email'],
                'senha' => $request['password'],
                'empresa' => $enterprise->nome,
                'url' => getenv('FRONT_URL')
            ];

            Mail::to($request['email'])->send(new NovoAdmin($customer_email));
            DB::commit();
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
        DB::beginTransaction();
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
            DB::commit();
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

    public function Customer(Request $request){
        if(!empty($request->email)){
            $customer = Customer::where('email', $request->email)->first();

            if(empty($customer)){
                return response([
                    'error' => 1,
                    'code' => 'customer_not_found',
                    'description' => 'Cadastro não encontrado'
                ], 404);
            }
        }elseif(!empty($request->uuid)){
            $customer = Customer::where('uuid', $request->uuid)->first();

            if(empty($customer)){
                return response([
                    'error' => 1,
                    'code' => 'customer_not_found',
                    'description' => 'Cadastro não encontrado'
                ], 404);
            }
        }elseif(empty($request->all())){
            return response([
                'error' => 1,
                'code' => 'invalid_request',
                'description' => 'É necessário enviar algum dado'
            ], 422);
        }

        return response([
            'error' => 0,
            'code' => 'customer_found',
            'description' => 'Cadastro encontrado',
            'data' => new CustomerResource($customer)
        ],200);
    }

    public function updatePass($customer_uuid, Request $request){
        try{
            $customer = Customer::where('uuid', $customer_uuid)->first();
            $user = $customer->user()->first();
            $user->update(['password' => Hash::make($request->password)]);
            return [
                'error' => 0,
                'description' => 'Senha atualizada com sucesso',
                'code' => 'update_pass'
            ];
        }catch(\Exception $e){
            Log::error('[Erro in update password]', [$e->getMessage(), $e->getFile(), $e->getLine()]);
            return [
                'error' => 1,
                'code' => 'invalid_request',
                'description' => 'houve um erro inesperado',
            ];
        }
    }
}
