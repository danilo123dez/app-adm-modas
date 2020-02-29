<?php

use App\Models\User;
use App\Models\Customer;
use App\Models\Enterprises;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

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
                'nome' => $faker->name
            ]);

            $customer = Customer::create([
                'email' => $faker->unique()->safeEmail,
                'cpf' => $faker->numberBetween('10000000000', '99999999999'),
                'nome' => $faker->unique()->safeEmail,
                'empresa_id' => $empresa->id
            ]);

            $user = User::create([
                'email' => $customer->email,
                'password' => $faker->password,
                'loginable_type' => Customer::class,
                'loginable_id' => $customer->id
            ]);

            $count_user++;
        }
    }
}
