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
                'half_kg' => 10,
                'one_kg' => 15,
                'one_half_kg' => 20,
                'two_kg' => 25,
                'two_half_kg' => 30,
                'three_kg' => 35,
            ],
            [ 
                'id' => 3,
                'name' => 'Kuwait',
                'symbol' => 'Dinar',
                'exchange_rate' => 100.00,
                'status' => 1,
                'code' => 'KW',
                'half_kg' => 10,
                'one_kg' => 15,
                'one_half_kg' => 20,
                'two_kg' => 25,
                'two_half_kg' => 30,
                'three_kg' => 35,
            ],
        ];

        Currency::insert($currencies);
    }
}
