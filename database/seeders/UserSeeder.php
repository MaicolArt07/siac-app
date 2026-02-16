<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Maicol Erick Arteaga Guzman',
            'email' => 'maicolarteaga0711@gmail.com',
            'password' => bcrypt('123456')
        ])->assignRole('Admin');

        User::create([
            'name' => 'Prueba Persona',
            'email' => 'persona123@gmail.com',
            'password' => bcrypt('123456')
        ])->assignRole('Persona');

        User::factory(9)->create();
    }
}
