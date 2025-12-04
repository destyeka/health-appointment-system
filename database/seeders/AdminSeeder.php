<?php

namespace Database\Seeders;

use App\Models\Admin;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'id_user' => 1,
                'name' => 'Vincent Putra',
                'phone' => '0812-3456-8901'
            ],
        ];

        foreach($admins as $admin) {
            Admin::create($admin);
        }
    }
}
