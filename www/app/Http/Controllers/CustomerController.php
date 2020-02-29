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
use LaravelLegends\PtBrValidator\Validator;

class CustomerController extends Controller
{


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
                'password' => $request['password']
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

    public function update($uuid, Request $request) {
        
        $customer = Customer::where('uuid', $uuid)->first();

        if(empty($customer)){
            return [
                'error' => 1,
                'code' => 'customer_not_found',
                'description' => 'Usuário não listado na base de dados'

            ];
        }
        
        $customer->update($request->all());
    }
}
