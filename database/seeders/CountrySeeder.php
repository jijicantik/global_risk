<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            [
                'name' => 'Germany',
                'code' => 'DE',
                'latitude' => 51.165691,
                'longitude' => 10.451526,
                'gdp' => 4456000000000,
                'inflation' => 2.5,
                'population' => 84000000,
                'currency_code' => 'EUR',
                'currency_name' => 'Euro',
                'region' => 'Europe',
                'language' => 'German',
            ],
            [
                'name' => 'China',
                'code' => 'CN',
                'latitude' => 35.86166,
                'longitude' => 104.195397,
                'gdp' => 17790000000000,
                'inflation' => 0.7,
                'population' => 1412000000,
                'currency_code' => 'CNY',
                'currency_name' => 'Renminbi',
                'region' => 'Asia',
                'language' => 'Chinese',
            ],
            [
                'name' => 'Indonesia',
                'code' => 'ID',
                'latitude' => -0.789275,
                'longitude' => 113.921327,
                'gdp' => 1371000000000,
                'inflation' => 2.8,
                'population' => 277000000,
                'currency_code' => 'IDR',
                'currency_name' => 'Indonesian Rupiah',
                'region' => 'Asia',
                'language' => 'Indonesian',
            ],
            [
                'name' => 'Australia',
                'code' => 'AU',
                'latitude' => -25.274398,
                'longitude' => 133.775136,
                'gdp' => 1702000000000,
                'inflation' => 3.6,
                'population' => 26000000,
                'currency_code' => 'AUD',
                'currency_name' => 'Australian Dollar',
                'region' => 'Oceania',
                'language' => 'English',
            ],
            [
                'name' => 'United States',
                'code' => 'US',
                'latitude' => 37.09024,
                'longitude' => -95.712891,
                'gdp' => 27360000000000,
                'inflation' => 3.1,
                'population' => 335000000,
                'currency_code' => 'USD',
                'currency_name' => 'US Dollar',
                'region' => 'Americas',
                'language' => 'English',
            ],
            [
                'name' => 'Singapore',
                'code' => 'SG',
                'latitude' => 1.352083,
                'longitude' => 103.819836,
                'gdp' => 501000000000,
                'inflation' => 4.8,
                'population' => 6000000,
                'currency_code' => 'SGD',
                'currency_name' => 'Singapore Dollar',
                'region' => 'Asia',
                'language' => 'English, Malay, Mandarin, Tamil',
            ],
            [
                'name' => 'Netherlands',
                'code' => 'NL',
                'latitude' => 52.132633,
                'longitude' => 5.291266,
                'gdp' => 1118000000000,
                'inflation' => 3.8,
                'population' => 18000000,
                'currency_code' => 'EUR',
                'currency_name' => 'Euro',
                'region' => 'Europe',
                'language' => 'Dutch',
            ],
        ];

        foreach ($countries as $c) {
            Country::updateOrCreate(['code' => $c['code']], $c);
        }
    }
}
