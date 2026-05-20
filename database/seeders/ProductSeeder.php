<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Product::create(['name' => 'Producto A', 'sku' => 'PA-001', 'price' => 100, 'stock' => 10]);
        Product::create(['name' => 'Producto B', 'sku' => 'PB-001', 'price' => 50, 'stock' => 25]);
    }
}
