<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'id' => 1,
                'name' => 'Playera',
                'description' => 'Batman La Serie Animada',
                'price' => 200.00,
                'sizes' => json_encode(["L","XL","S","M"]),
                'colors' => json_encode(["Negro"]),
                'images' => json_encode(["products/irqfA9eQOTcogF1nNtUFYZYjFArmObdeGxzaDsJO.png"]),
                'stock' => 1,
                'stock_por_talla' => json_encode([
                    "L" => 1,
                    "XL" => 1,
                    "S" => 1,
                    "M" => 1
                ]),
                'is_active' => true,
                'categoria' => null,
                'expires_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id' => 2,
                'name' => 'Playera Mamá',
                'description' => 'Playera de Mamá con Mario Bros',
                'price' => 200.00,
                'sizes' => json_encode(["S","M","L","XL","XXL"]),
                'colors' => json_encode(["Negro"]),
                'images' => json_encode(["products/BBVtgf6CSjSNk4qXl8WdM1YeIU7hg1UMaRyZEjTI.png"]),
                'stock' => 1,
                'stock_por_talla' => json_encode([]),
                'is_active' => true,
                'categoria' => 'Día de las madres',
                'expires_at' => '2026-05-15 07:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id' => 3,
                'name' => 'Playera Mamá',
                'description' => 'La mamá mas bonita',
                'price' => 200.00,
                'sizes' => json_encode(["M","L","XL","S"]),
                'colors' => json_encode(["Negro"]),
                'images' => json_encode(["products/aq2MAHotxFzJfwA8iusRjTyu6NN5NoF4gWgPZALa.png"]),
                'stock' => 1,
                'stock_por_talla' => json_encode([
                    "S" => 0,
                    "M" => 0,
                    "L" => 0,
                    "XL" => 0
                ]),
                'is_active' => true,
                'categoria' => 'Día de las madres',
                'expires_at' => '2026-05-15 07:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}