<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Manyavar',    'image' => '1771388626.jpg'],
            ['name' => 'Fabindia',    'image' => '1771399298.jpg'],
            ['name' => 'W for Woman', 'image' => '1771407065.jpg'],
            ['name' => 'Biba',        'image' => '1772189127.png'],
            ['name' => 'Allen Solly', 'image' => '1774332035.jpg'],
            ['name' => 'Van Heusen',  'image' => '1774421609.jpg'],
            ['name' => 'H&M',         'image' => '1774422324.webp'],
            ['name' => 'Zara',        'image' => '1774422563.jpg'],
        ];

        foreach ($brands as $brand) {
            DB::table('brands')->insert([
                'name'       => $brand['name'],
                'image'      => $brand['image'],
                'status'     => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
