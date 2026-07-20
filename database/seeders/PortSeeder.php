<?php

namespace Database\Seeders;

use App\Models\Port;
use Illuminate\Database\Seeder;

class PortSeeder extends Seeder
{
    public function run(): void
    {
        $ports = [
            [
                'name' => 'Port of Rotterdam',
                'code' => 'NLRTM',
                'latitude' => 51.924420,
                'longitude' => 4.477733,
                'country_code' => 'NL',
                'country_name' => 'Netherlands',
            ],
            [
                'name' => 'Port of Hamburg',
                'code' => 'DEHAM',
                'latitude' => 53.551086,
                'longitude' => 9.993682,
                'country_code' => 'DE',
                'country_name' => 'Germany',
            ],
            [
                'name' => 'Port of Shanghai',
                'code' => 'CNSHA',
                'latitude' => 31.230416,
                'longitude' => 121.473701,
                'country_code' => 'CN',
                'country_name' => 'China',
            ],
            [
                'name' => 'Port of Singapore',
                'code' => 'SGSIN',
                'latitude' => 1.264000,
                'longitude' => 103.840000,
                'country_code' => 'SG',
                'country_name' => 'Singapore',
            ],
            [
                'name' => 'Port of Tanjung Priok (Jakarta)',
                'code' => 'IDTPP',
                'latitude' => -6.103300,
                'longitude' => 106.879200,
                'country_code' => 'ID',
                'country_name' => 'Indonesia',
            ],
            [
                'name' => 'Port of Tanjung Perak (Surabaya)',
                'code' => 'IDSUB',
                'latitude' => -7.202500,
                'longitude' => 112.724200,
                'country_code' => 'ID',
                'country_name' => 'Indonesia',
            ],
            [
                'name' => 'Port of Los Angeles',
                'code' => 'USLAX',
                'latitude' => 33.740000,
                'longitude' => -118.260000,
                'country_code' => 'US',
                'country_name' => 'United States',
            ],
            [
                'name' => 'Port of Sydney',
                'code' => 'AUSYD',
                'latitude' => -33.868820,
                'longitude' => 151.209296,
                'country_code' => 'AU',
                'country_name' => 'Australia',
            ],
            [
                'name' => 'Port of Ningbo-Zhoushan',
                'code' => 'CNNGB',
                'latitude' => 29.868300,
                'longitude' => 121.544000,
                'country_code' => 'CN',
                'country_name' => 'China',
            ],
            [
                'name' => 'Port of Shenzhen',
                'code' => 'CNSZX',
                'latitude' => 22.543100,
                'longitude' => 114.057900,
                'country_code' => 'CN',
                'country_name' => 'China',
            ],
        ];

        foreach ($ports as $p) {
            Port::updateOrCreate(['code' => $p['code']], $p);
        }
    }
}
