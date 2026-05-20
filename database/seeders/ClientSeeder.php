<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Client::create(['name' => 'Cliente Demo', 'email' => 'cliente@example.com', 'document' => '12345678']);
        Client::create(['name' => 'Empresa S.A.', 'email' => 'ventas@example.com', 'document' => '87654321']);
    }
}
