<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $currencies = [
            [ 
                'id' => 1,
                'name' => 'Egypt',
                'symbol' => 'EGP',
                'exchange_rate' => 1.00,
                'status' => 1,
                'code' => 'EG',
                'half_kg' => 0,
                'one_kg' => 0,
                'one_half_kg' => 0,
                'two_kg' => 0,
                'two_half_kg' => 0,
                'three_kg' => 0,
            ],
            [ 
                'id' => 2,
                'name' => 'United Arab Emirates',
                'symbol' => 'Dir',
                'exchange_rate' => 9.00,
                'status' => 1,
                'code' => 'AE',
                'half_kg' => 90,
                'one_kg' => 170,
                'one_half_kg' => 230,
                'two_kg' => 300,
                'two_half_kg' => 350,
                'three_kg' => 410,
            ],
            [ 
                'id' => 3,
                'name' => 'Kuwait',
                'symbol' => 'Dinar',
                'exchange_rate' => 100.00,
                'status' => 1,
                'code' => 'KW',
                'half_kg' => 1000,
                'one_kg' => 1500,
                'one_half_kg' => 2000,
                'two_kg' => 2500,
                'two_half_kg' => 3000,
                'three_kg' => 3500,
            ],
            [ 
                'id' => 4,
                'name' => 'Saudi',
                'symbol' => 'SAR',
                'exchange_rate' => 8.00,
                'status' => 1,
                'code' => 'SA',
                'half_kg' => 80,
                'one_kg' => 150,
                'one_half_kg' => 200,
                'two_kg' => 250,
                'two_half_kg' => 300,
                'three_kg' => 450,
            ],
        ];

        Currency::insert($currencies);
    }
}
