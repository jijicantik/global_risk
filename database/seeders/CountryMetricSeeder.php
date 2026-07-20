<?php

namespace Database\Seeders;

use App\Models\CountryMetric;
use Illuminate\Database\Seeder;

class CountryMetricSeeder extends Seeder
{
    public function run(): void
    {
        $metrics = [
            // Germany (DE)
            ['country_code' => 'DE', 'year' => 2020, 'gdp' => 3889000000000, 'inflation' => 0.5, 'currency_rate' => 0.88, 'risk_score' => 15],
            ['country_code' => 'DE', 'year' => 2021, 'gdp' => 4260000000000, 'inflation' => 3.1, 'currency_rate' => 0.84, 'risk_score' => 18],
            ['country_code' => 'DE', 'year' => 2022, 'gdp' => 4070000000000, 'inflation' => 6.9, 'currency_rate' => 0.95, 'risk_score' => 35],
            ['country_code' => 'DE', 'year' => 2023, 'gdp' => 4456000000000, 'inflation' => 5.9, 'currency_rate' => 0.92, 'risk_score' => 28],
            ['country_code' => 'DE', 'year' => 2024, 'gdp' => 4500000000000, 'inflation' => 2.5, 'currency_rate' => 0.91, 'risk_score' => 22],

            // China (CN)
            ['country_code' => 'CN', 'year' => 2020, 'gdp' => 14680000000000, 'inflation' => 2.5, 'currency_rate' => 6.90, 'risk_score' => 25],
            ['country_code' => 'CN', 'year' => 2021, 'gdp' => 17730000000000, 'inflation' => 0.9, 'currency_rate' => 6.45, 'risk_score' => 30],
            ['country_code' => 'CN', 'year' => 2022, 'gdp' => 17960000000000, 'inflation' => 2.0, 'currency_rate' => 6.72, 'risk_score' => 45],
            ['country_code' => 'CN', 'year' => 2023, 'gdp' => 17790000000000, 'inflation' => 0.2, 'currency_rate' => 7.08, 'risk_score' => 48],
            ['country_code' => 'CN', 'year' => 2024, 'gdp' => 18500000000000, 'inflation' => 0.7, 'currency_rate' => 7.20, 'risk_score' => 42],

            // Indonesia (ID)
            ['country_code' => 'ID', 'year' => 2020, 'gdp' => 1058000000000, 'inflation' => 2.0, 'currency_rate' => 14580, 'risk_score' => 28],
            ['country_code' => 'ID', 'year' => 2021, 'gdp' => 1186000000000, 'inflation' => 1.6, 'currency_rate' => 14310, 'risk_score' => 24],
            ['country_code' => 'ID', 'year' => 2022, 'gdp' => 1319000000000, 'inflation' => 4.2, 'currency_rate' => 14850, 'risk_score' => 38],
            ['country_code' => 'ID', 'year' => 2023, 'gdp' => 1371000000000, 'inflation' => 3.7, 'currency_rate' => 15240, 'risk_score' => 34],
            ['country_code' => 'ID', 'year' => 2024, 'gdp' => 1420000000000, 'inflation' => 2.8, 'currency_rate' => 15600, 'risk_score' => 29],

            // Australia (AU)
            ['country_code' => 'AU', 'year' => 2020, 'gdp' => 1327000000000, 'inflation' => 0.9, 'currency_rate' => 1.45, 'risk_score' => 18],
            ['country_code' => 'AU', 'year' => 2021, 'gdp' => 1543000000000, 'inflation' => 2.9, 'currency_rate' => 1.33, 'risk_score' => 20],
            ['country_code' => 'AU', 'year' => 2022, 'gdp' => 1675000000000, 'inflation' => 6.6, 'currency_rate' => 1.44, 'risk_score' => 32],
            ['country_code' => 'AU', 'year' => 2023, 'gdp' => 1702000000000, 'inflation' => 5.6, 'currency_rate' => 1.51, 'risk_score' => 30],
            ['country_code' => 'AU', 'year' => 2024, 'gdp' => 1750000000000, 'inflation' => 3.6, 'currency_rate' => 1.50, 'risk_score' => 25],

            // United States (US)
            ['country_code' => 'US', 'year' => 2020, 'gdp' => 21060000000000, 'inflation' => 1.2, 'currency_rate' => 1.0, 'risk_score' => 20],
            ['country_code' => 'US', 'year' => 2021, 'gdp' => 23320000000000, 'inflation' => 4.7, 'currency_rate' => 1.0, 'risk_score' => 25],
            ['country_code' => 'US', 'year' => 2022, 'gdp' => 25740000000000, 'inflation' => 8.0, 'currency_rate' => 1.0, 'risk_score' => 42],
            ['country_code' => 'US', 'year' => 2023, 'gdp' => 27360000000000, 'inflation' => 4.1, 'currency_rate' => 1.0, 'risk_score' => 32],
            ['country_code' => 'US', 'year' => 2024, 'gdp' => 28200000000000, 'inflation' => 3.1, 'currency_rate' => 1.0, 'risk_score' => 24],

            // Singapore (SG)
            ['country_code' => 'SG', 'year' => 2020, 'gdp' => 345000000000, 'inflation' => -0.2, 'currency_rate' => 1.38, 'risk_score' => 15],
            ['country_code' => 'SG', 'year' => 2021, 'gdp' => 424000000000, 'inflation' => 2.3, 'currency_rate' => 1.34, 'risk_score' => 17],
            ['country_code' => 'SG', 'year' => 2022, 'gdp' => 473000000000, 'inflation' => 6.1, 'currency_rate' => 1.38, 'risk_score' => 28],
            ['country_code' => 'SG', 'year' => 2023, 'gdp' => 501000000000, 'inflation' => 4.8, 'currency_rate' => 1.34, 'risk_score' => 22],
            ['country_code' => 'SG', 'year' => 2024, 'gdp' => 520000000000, 'inflation' => 3.0, 'currency_rate' => 1.33, 'risk_score' => 18],

            // Netherlands (NL)
            ['country_code' => 'NL', 'year' => 2020, 'gdp' => 909000000000, 'inflation' => 1.3, 'currency_rate' => 0.88, 'risk_score' => 14],
            ['country_code' => 'NL', 'year' => 2021, 'gdp' => 1018000000000, 'inflation' => 2.7, 'currency_rate' => 0.84, 'risk_score' => 16],
            ['country_code' => 'NL', 'year' => 2022, 'gdp' => 1010000000000, 'inflation' => 10.0, 'currency_rate' => 0.95, 'risk_score' => 38],
            ['country_code' => 'NL', 'year' => 2023, 'gdp' => 1118000000000, 'inflation' => 3.8, 'currency_rate' => 0.92, 'risk_score' => 25],
            ['country_code' => 'NL', 'year' => 2024, 'gdp' => 1150000000000, 'inflation' => 2.8, 'currency_rate' => 0.91, 'risk_score' => 19],
        ];

        foreach ($metrics as $m) {
            CountryMetric::updateOrCreate(
                ['country_code' => $m['country_code'], 'year' => $m['year']],
                $m
            );
        }
    }
}
