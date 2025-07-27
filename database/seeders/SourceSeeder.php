<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sources')->insert([
            [
                'name' => 'openfoodfacts',
                'url' => 'https://world.openfoodfacts.org/'
            ],
            [
                'name' => 'barcode-lookup',
                'url' => 'https://www.barcodelookup.com/'
            ],
            [
                'name' => 'tarraco-import-export',
                'url' => 'https://tienda.tarracoimportexport.com/index.php?id_lang=1'
            ],
        ]);
    }
}
