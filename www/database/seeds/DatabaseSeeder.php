<?php

use App\Models\User;
use App\Models\Customer;
use App\Models\Enterprises;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $count_user = 0;

        while($count_user < 5){

            $empresa = Enterprises::create([
                'nome' => $faker->name,
                'email_empresa' => $faker->unique()->safeEmail
            ]);

            $customer = Customer::create([
                'email' => $faker->unique()->safeEmail,
                'cpf' => $faker->numberBetween('10000000000', '99999999999'),
                'nome' => $faker->name,
                'numero' => 11946083396,
                'empresa_id' => $empresa->id
            ]);

            $user = User::create([
                'email' => $customer->email,
                'password' => 123,
                'loginable_type' => Customer::class,
                'loginable_id' => $customer->id
            ]);

            $count_user++;
        }
        Customer::create([
            'email' => 'danfranceschi231@gmail.com',
            'cpf' => $faker->numberBetween('10000000000', '99999999999'),
            'nome' => 'Danilo Franceschi',
            'numero' => 11946083396,
            'empresa_id' => 5
        ]);

        User::create([
            'email' => 'danfranceschi231@gmail.com',
            'password' => 123,
            'loginable_type' => Customer::class,
            'loginable_id' => 6
        ]);
    }
}
