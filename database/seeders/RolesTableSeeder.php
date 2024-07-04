<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        // Criação de papéis
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'moderator']);
        Role::create(['name' => 'user']);
    }
}
