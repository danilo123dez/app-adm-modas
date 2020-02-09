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
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => $faker->password,
            ]);

            $empresa = Enterprises::create([
                'nome' => $faker->name
            ]);

            $customer = Customer::create([
                'email' => $user->email,
                'cpf' => $faker->numberBetween('10000000000', '99999999999'),
                'nome' => $faker->unique()->safeEmail,
                'empresa_id' => $empresa->id
            ]);

            $count_user++;
        }
    }
}
