<?php

use App\Models\EngineType;
use Illuminate\Database\Seeder;

class EngineTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $engine_types = [
            [
                'name' => 'Gasolina',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Diesel',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Eléctrico',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ];
        
        EngineType::insert($engine_types);
        
    }
}
