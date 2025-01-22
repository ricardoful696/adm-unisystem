<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class EmpresasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('pt_BR');

        foreach (range(1, 10) as $index) {
            DB::table('empresas')->insert([
                'id' => Str::uuid(),
                'cnpj' => $faker->numerify('##.###.###/####-##'),
                'nome' => $faker->company,
                'nome_fantasia' => $faker->companySuffix,
                'telefone1' => $faker->phoneNumber,
                'telefone2' => $faker->optional()->phoneNumber,
                'email' => $faker->companyEmail,
                'cep' => $faker->postcode,
                'estado' => $faker->stateAbbr,
                'cidade' => $faker->city,
                'endereco' => $faker->streetName,
                'endereco_numero' => $faker->buildingNumber,
                'endereco_complemento' => $faker->optional()->secondaryAddress,
                'url_capa' => $faker->optional()->imageUrl(),
                'url_site' => $faker->optional()->url,
                'url_facebook' => $faker->optional()->url,
                'url_instagram' => $faker->optional()->url,
                'url_googleplus' => $faker->optional()->url,
                'url_youtube' => $faker->optional()->url,
                'url_promocional' => $faker->optional()->url,
                'url_regras' => $faker->optional()->url,
                'ativo' => $faker->boolean,
                'realm' => Str::random(10),
                'client_key' => Str::random(20),
                'public_key' => Str::random(20),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
