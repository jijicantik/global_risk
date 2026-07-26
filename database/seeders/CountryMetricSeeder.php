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
            ['country_code' => 'DE', 'year' => 2025, 'gdp' => 4580000000000, 'inflation' => 2.2, 'currency_rate' => 0.90, 'risk_score' => 20],
            ['country_code' => 'DE', 'year' => 2026, 'gdp' => 4650000000000, 'inflation' => 2.0, 'currency_rate' => 0.89, 'risk_score' => 18],

            // China (CN)
            ['country_code' => 'CN', 'year' => 2020, 'gdp' => 14680000000000, 'inflation' => 2.5, 'currency_rate' => 6.90, 'risk_score' => 25],
            ['country_code' => 'CN', 'year' => 2021, 'gdp' => 17730000000000, 'inflation' => 0.9, 'currency_rate' => 6.45, 'risk_score' => 30],
            ['country_code' => 'CN', 'year' => 2022, 'gdp' => 17960000000000, 'inflation' => 2.0, 'currency_rate' => 6.72, 'risk_score' => 45],
            ['country_code' => 'CN', 'year' => 2023, 'gdp' => 17790000000000, 'inflation' => 0.2, 'currency_rate' => 7.08, 'risk_score' => 48],
            ['country_code' => 'CN', 'year' => 2024, 'gdp' => 18500000000000, 'inflation' => 0.7, 'currency_rate' => 7.20, 'risk_score' => 42],
            ['country_code' => 'CN', 'year' => 2025, 'gdp' => 19100000000000, 'inflation' => 1.0, 'currency_rate' => 7.15, 'risk_score' => 40],
            ['country_code' => 'CN', 'year' => 2026, 'gdp' => 19800000000000, 'inflation' => 1.2, 'currency_rate' => 7.10, 'risk_score' => 38],

            // Indonesia (ID)
            ['country_code' => 'ID', 'year' => 2020, 'gdp' => 1058000000000, 'inflation' => 2.0, 'currency_rate' => 14580, 'risk_score' => 28],
            ['country_code' => 'ID', 'year' => 2021, 'gdp' => 1186000000000, 'inflation' => 1.6, 'currency_rate' => 14310, 'risk_score' => 24],
            ['country_code' => 'ID', 'year' => 2022, 'gdp' => 1319000000000, 'inflation' => 4.2, 'currency_rate' => 14850, 'risk_score' => 38],
            ['country_code' => 'ID', 'year' => 2023, 'gdp' => 1371000000000, 'inflation' => 3.7, 'currency_rate' => 15240, 'risk_score' => 34],
            ['country_code' => 'ID', 'year' => 2024, 'gdp' => 1420000000000, 'inflation' => 2.8, 'currency_rate' => 15600, 'risk_score' => 29],
            ['country_code' => 'ID', 'year' => 2025, 'gdp' => 1480000000000, 'inflation' => 2.5, 'currency_rate' => 15400, 'risk_score' => 27],
            ['country_code' => 'ID', 'year' => 2026, 'gdp' => 1550000000000, 'inflation' => 2.4, 'currency_rate' => 15200, 'risk_score' => 25],

            // Australia (AU)
            ['country_code' => 'AU', 'year' => 2020, 'gdp' => 1327000000000, 'inflation' => 0.9, 'currency_rate' => 1.45, 'risk_score' => 18],
            ['country_code' => 'AU', 'year' => 2021, 'gdp' => 1543000000000, 'inflation' => 2.9, 'currency_rate' => 1.33, 'risk_score' => 20],
            ['country_code' => 'AU', 'year' => 2022, 'gdp' => 1675000000000, 'inflation' => 6.6, 'currency_rate' => 1.44, 'risk_score' => 32],
            ['country_code' => 'AU', 'year' => 2023, 'gdp' => 1702000000000, 'inflation' => 5.6, 'currency_rate' => 1.51, 'risk_score' => 30],
            ['country_code' => 'AU', 'year' => 2024, 'gdp' => 1750000000000, 'inflation' => 3.6, 'currency_rate' => 1.50, 'risk_score' => 25],
            ['country_code' => 'AU', 'year' => 2025, 'gdp' => 1800000000000, 'inflation' => 3.2, 'currency_rate' => 1.48, 'risk_score' => 22],
            ['country_code' => 'AU', 'year' => 2026, 'gdp' => 1850000000000, 'inflation' => 2.8, 'currency_rate' => 1.46, 'risk_score' => 20],

            // United States (US)
            ['country_code' => 'US', 'year' => 2020, 'gdp' => 21060000000000, 'inflation' => 1.2, 'currency_rate' => 1.0, 'risk_score' => 20],
            ['country_code' => 'US', 'year' => 2021, 'gdp' => 23320000000000, 'inflation' => 4.7, 'currency_rate' => 1.0, 'risk_score' => 25],
            ['country_code' => 'US', 'year' => 2022, 'gdp' => 25740000000000, 'inflation' => 8.0, 'currency_rate' => 1.0, 'risk_score' => 42],
            ['country_code' => 'US', 'year' => 2023, 'gdp' => 27360000000000, 'inflation' => 4.1, 'currency_rate' => 1.0, 'risk_score' => 32],
            ['country_code' => 'US', 'year' => 2024, 'gdp' => 28200000000000, 'inflation' => 3.1, 'currency_rate' => 1.0, 'risk_score' => 24],
            ['country_code' => 'US', 'year' => 2025, 'gdp' => 29100000000000, 'inflation' => 2.8, 'currency_rate' => 1.0, 'risk_score' => 22],
            ['country_code' => 'US', 'year' => 2026, 'gdp' => 30000000000000, 'inflation' => 2.4, 'currency_rate' => 1.0, 'risk_score' => 20],

            // Singapore (SG)
            ['country_code' => 'SG', 'year' => 2020, 'gdp' => 34500000000, 'inflation' => -0.2, 'currency_rate' => 1.38, 'risk_score' => 15],
            ['country_code' => 'SG', 'year' => 2021, 'gdp' => 42400000000, 'inflation' => 2.3, 'currency_rate' => 1.34, 'risk_score' => 17],
            ['country_code' => 'SG', 'year' => 2022, 'gdp' => 47300000000, 'inflation' => 6.1, 'currency_rate' => 1.38, 'risk_score' => 28],
            ['country_code' => 'SG', 'year' => 2023, 'gdp' => 50100000000, 'inflation' => 4.8, 'currency_rate' => 1.34, 'risk_score' => 22],
            ['country_code' => 'SG', 'year' => 2024, 'gdp' => 52000000000, 'inflation' => 3.0, 'currency_rate' => 1.33, 'risk_score' => 18],
            ['country_code' => 'SG', 'year' => 2025, 'gdp' => 54000000000, 'inflation' => 2.8, 'currency_rate' => 1.32, 'risk_score' => 16],
            ['country_code' => 'SG', 'year' => 2026, 'gdp' => 56000000000, 'inflation' => 2.5, 'currency_rate' => 1.31, 'risk_score' => 15],

            // Netherlands (NL)
            ['country_code' => 'NL', 'year' => 2020, 'gdp' => 90900000000, 'inflation' => 1.3, 'currency_rate' => 0.88, 'risk_score' => 14],
            ['country_code' => 'NL', 'year' => 2021, 'gdp' => 101800000000, 'inflation' => 2.7, 'currency_rate' => 0.84, 'risk_score' => 16],
            ['country_code' => 'NL', 'year' => 2022, 'gdp' => 101000000000, 'inflation' => 10.0, 'currency_rate' => 0.95, 'risk_score' => 38],
            ['country_code' => 'NL', 'year' => 2023, 'gdp' => 111800000000, 'inflation' => 3.8, 'currency_rate' => 0.92, 'risk_score' => 25],
            ['country_code' => 'NL', 'year' => 2024, 'gdp' => 115000000000, 'inflation' => 2.8, 'currency_rate' => 0.91, 'risk_score' => 19],
            ['country_code' => 'NL', 'year' => 2025, 'gdp' => 118000000000, 'inflation' => 2.6, 'currency_rate' => 0.90, 'risk_score' => 17],
            ['country_code' => 'NL', 'year' => 2026, 'gdp' => 122000000000, 'inflation' => 2.2, 'currency_rate' => 0.89, 'risk_score' => 15],

            // Saudi Arabia (SA)
            ['country_code' => 'SA', 'year' => 2020, 'gdp' => 700000000000, 'inflation' => 3.4, 'currency_rate' => 3.75, 'risk_score' => 25],
            ['country_code' => 'SA', 'year' => 2021, 'gdp' => 840000000000, 'inflation' => 3.1, 'currency_rate' => 3.75, 'risk_score' => 24],
            ['country_code' => 'SA', 'year' => 2022, 'gdp' => 1100000000000, 'inflation' => 2.5, 'currency_rate' => 3.75, 'risk_score' => 20],
            ['country_code' => 'SA', 'year' => 2023, 'gdp' => 1070000000000, 'inflation' => 2.3, 'currency_rate' => 3.75, 'risk_score' => 22],
            ['country_code' => 'SA', 'year' => 2024, 'gdp' => 1110000000000, 'inflation' => 1.6, 'currency_rate' => 3.75, 'risk_score' => 21],
            ['country_code' => 'SA', 'year' => 2025, 'gdp' => 1150000000000, 'inflation' => 1.8, 'currency_rate' => 3.75, 'risk_score' => 20],
            ['country_code' => 'SA', 'year' => 2026, 'gdp' => 1200000000000, 'inflation' => 1.5, 'currency_rate' => 3.75, 'risk_score' => 18],

            // Bahrain (BH)
            ['country_code' => 'BH', 'year' => 2020, 'gdp' => 34000000000, 'inflation' => -2.3, 'currency_rate' => 0.38, 'risk_score' => 22],
            ['country_code' => 'BH', 'year' => 2021, 'gdp' => 39000000000, 'inflation' => 1.5, 'currency_rate' => 0.38, 'risk_score' => 20],
            ['country_code' => 'BH', 'year' => 2022, 'gdp' => 44000000000, 'inflation' => 3.6, 'currency_rate' => 0.38, 'risk_score' => 22],
            ['country_code' => 'BH', 'year' => 2023, 'gdp' => 43000000000, 'inflation' => 1.7, 'currency_rate' => 0.38, 'risk_score' => 21],
            ['country_code' => 'BH', 'year' => 2024, 'gdp' => 45000000000, 'inflation' => 1.5, 'currency_rate' => 0.38, 'risk_score' => 19],
            ['country_code' => 'BH', 'year' => 2025, 'gdp' => 47000000000, 'inflation' => 1.4, 'currency_rate' => 0.38, 'risk_score' => 18],
            ['country_code' => 'BH', 'year' => 2026, 'gdp' => 49000000000, 'inflation' => 1.2, 'currency_rate' => 0.38, 'risk_score' => 17],

            // Iran (IR)
            ['country_code' => 'IR', 'year' => 2020, 'gdp' => 200000000000, 'inflation' => 47.0, 'currency_rate' => 42000, 'risk_score' => 65],
            ['country_code' => 'IR', 'year' => 2021, 'gdp' => 290000000000, 'inflation' => 43.0, 'currency_rate' => 42000, 'risk_score' => 62],
            ['country_code' => 'IR', 'year' => 2022, 'gdp' => 350000000000, 'inflation' => 49.0, 'currency_rate' => 42000, 'risk_score' => 70],
            ['country_code' => 'IR', 'year' => 2023, 'gdp' => 360000000000, 'inflation' => 45.0, 'currency_rate' => 42000, 'risk_score' => 68],
            ['country_code' => 'IR', 'year' => 2024, 'gdp' => 380000000000, 'inflation' => 40.0, 'currency_rate' => 42000, 'risk_score' => 64],
            ['country_code' => 'IR', 'year' => 2025, 'gdp' => 390000000000, 'inflation' => 38.0, 'currency_rate' => 42000, 'risk_score' => 60],
            ['country_code' => 'IR', 'year' => 2026, 'gdp' => 410000000000, 'inflation' => 35.0, 'currency_rate' => 42000, 'risk_score' => 58],

            // Iraq (IQ)
            ['country_code' => 'IQ', 'year' => 2020, 'gdp' => 180000000000, 'inflation' => 0.6, 'currency_rate' => 1190, 'risk_score' => 55],
            ['country_code' => 'IQ', 'year' => 2021, 'gdp' => 207000000000, 'inflation' => 6.0, 'currency_rate' => 1450, 'risk_score' => 50],
            ['country_code' => 'IQ', 'year' => 2022, 'gdp' => 264000000000, 'inflation' => 5.0, 'currency_rate' => 1450, 'risk_score' => 48],
            ['country_code' => 'IQ', 'year' => 2023, 'gdp' => 250000000000, 'inflation' => 4.4, 'currency_rate' => 1450, 'risk_score' => 46],
            ['country_code' => 'IQ', 'year' => 2024, 'gdp' => 260000000000, 'inflation' => 3.5, 'currency_rate' => 1450, 'risk_score' => 44],
            ['country_code' => 'IQ', 'year' => 2025, 'gdp' => 270000000000, 'inflation' => 3.2, 'currency_rate' => 1450, 'risk_score' => 42],
            ['country_code' => 'IQ', 'year' => 2026, 'gdp' => 280000000000, 'inflation' => 3.0, 'currency_rate' => 1450, 'risk_score' => 40],

            // Israel (IL)
            ['country_code' => 'IL', 'year' => 2020, 'gdp' => 413000000000, 'inflation' => -0.6, 'currency_rate' => 3.44, 'risk_score' => 28],
            ['country_code' => 'IL', 'year' => 2021, 'gdp' => 488000000000, 'inflation' => 1.5, 'currency_rate' => 3.23, 'risk_score' => 25],
            ['country_code' => 'IL', 'year' => 2022, 'gdp' => 525000000000, 'inflation' => 4.4, 'currency_rate' => 3.36, 'risk_score' => 30],
            ['country_code' => 'IL', 'year' => 2023, 'gdp' => 520000000000, 'inflation' => 3.8, 'currency_rate' => 3.68, 'risk_score' => 45],
            ['country_code' => 'IL', 'year' => 2024, 'gdp' => 530000000000, 'inflation' => 2.8, 'currency_rate' => 3.72, 'risk_score' => 42],
            ['country_code' => 'IL', 'year' => 2025, 'gdp' => 545000000000, 'inflation' => 2.5, 'currency_rate' => 3.65, 'risk_score' => 38],
            ['country_code' => 'IL', 'year' => 2026, 'gdp' => 560000000000, 'inflation' => 2.2, 'currency_rate' => 3.60, 'risk_score' => 35],

            // Kuwait (KW)
            ['country_code' => 'KW', 'year' => 2020, 'gdp' => 105000000000, 'inflation' => 2.1, 'currency_rate' => 0.31, 'risk_score' => 25],
            ['country_code' => 'KW', 'year' => 2021, 'gdp' => 136000000000, 'inflation' => 3.4, 'currency_rate' => 0.30, 'risk_score' => 23],
            ['country_code' => 'KW', 'year' => 2022, 'gdp' => 175000000000, 'inflation' => 4.0, 'currency_rate' => 0.31, 'risk_score' => 20],
            ['country_code' => 'KW', 'year' => 2023, 'gdp' => 160000000000, 'inflation' => 3.6, 'currency_rate' => 0.31, 'risk_score' => 22],
            ['country_code' => 'KW', 'year' => 2024, 'gdp' => 165000000000, 'inflation' => 3.2, 'currency_rate' => 0.31, 'risk_score' => 21],
            ['country_code' => 'KW', 'year' => 2025, 'gdp' => 172000000000, 'inflation' => 3.0, 'currency_rate' => 0.31, 'risk_score' => 20],
            ['country_code' => 'KW', 'year' => 2026, 'gdp' => 180000000000, 'inflation' => 2.8, 'currency_rate' => 0.31, 'risk_score' => 18],

            // Lebanon (LB)
            ['country_code' => 'LB', 'year' => 2020, 'gdp' => 31000000000, 'inflation' => 84.9, 'currency_rate' => 1507, 'risk_score' => 80],
            ['country_code' => 'LB', 'year' => 2021, 'gdp' => 24000000000, 'inflation' => 154.8, 'currency_rate' => 1507, 'risk_score' => 85],
            ['country_code' => 'LB', 'year' => 2022, 'gdp' => 22000000000, 'inflation' => 171.2, 'currency_rate' => 1507, 'risk_score' => 90],
            ['country_code' => 'LB', 'year' => 2023, 'gdp' => 20000000000, 'inflation' => 150.0, 'currency_rate' => 15000, 'risk_score' => 88],
            ['country_code' => 'LB', 'year' => 2024, 'gdp' => 19000000000, 'inflation' => 100.0, 'currency_rate' => 89000, 'risk_score' => 85],
            ['country_code' => 'LB', 'year' => 2025, 'gdp' => 18000000000, 'inflation' => 80.0, 'currency_rate' => 90000, 'risk_score' => 80],
            ['country_code' => 'LB', 'year' => 2026, 'gdp' => 18000000000, 'inflation' => 60.0, 'currency_rate' => 90000, 'risk_score' => 75],

            // Oman (OM)
            ['country_code' => 'OM', 'year' => 2020, 'gdp' => 76000000000, 'inflation' => -0.9, 'currency_rate' => 0.39, 'risk_score' => 28],
            ['country_code' => 'OM', 'year' => 2021, 'gdp' => 88000000000, 'inflation' => 1.5, 'currency_rate' => 0.39, 'risk_score' => 25],
            ['country_code' => 'OM', 'year' => 2022, 'gdp' => 114000000000, 'inflation' => 2.8, 'currency_rate' => 0.39, 'risk_score' => 22],
            ['country_code' => 'OM', 'year' => 2023, 'gdp' => 108000000000, 'inflation' => 1.7, 'currency_rate' => 0.39, 'risk_score' => 23],
            ['country_code' => 'OM', 'year' => 2024, 'gdp' => 112000000000, 'inflation' => 1.5, 'currency_rate' => 0.39, 'risk_score' => 21],
            ['country_code' => 'OM', 'year' => 2025, 'gdp' => 116000000000, 'inflation' => 1.4, 'currency_rate' => 0.39, 'risk_score' => 20],
            ['country_code' => 'OM', 'year' => 2026, 'gdp' => 120000000000, 'inflation' => 1.2, 'currency_rate' => 0.39, 'risk_score' => 18],

            // Palestine (PS)
            ['country_code' => 'PS', 'year' => 2020, 'gdp' => 15000000000, 'inflation' => -0.7, 'currency_rate' => 3.44, 'risk_score' => 60],
            ['country_code' => 'PS', 'year' => 2021, 'gdp' => 18000000000, 'inflation' => 1.2, 'currency_rate' => 3.23, 'risk_score' => 55],
            ['country_code' => 'PS', 'year' => 2022, 'gdp' => 19000000000, 'inflation' => 3.8, 'currency_rate' => 3.36, 'risk_score' => 58],
            ['country_code' => 'PS', 'year' => 2023, 'gdp' => 18000000000, 'inflation' => 4.0, 'currency_rate' => 3.68, 'risk_score' => 85],
            ['country_code' => 'PS', 'year' => 2024, 'gdp' => 17000000000, 'inflation' => 5.0, 'currency_rate' => 3.72, 'risk_score' => 88],
            ['country_code' => 'PS', 'year' => 2025, 'gdp' => 16000000000, 'inflation' => 4.5, 'currency_rate' => 3.65, 'risk_score' => 85],
            ['country_code' => 'PS', 'year' => 2026, 'gdp' => 17000000000, 'inflation' => 3.8, 'currency_rate' => 3.60, 'risk_score' => 80],

            // Qatar (QA)
            ['country_code' => 'QA', 'year' => 2020, 'gdp' => 144000000000, 'inflation' => -2.5, 'currency_rate' => 3.64, 'risk_score' => 22],
            ['country_code' => 'QA', 'year' => 2021, 'gdp' => 180000000000, 'inflation' => 2.3, 'currency_rate' => 3.64, 'risk_score' => 20],
            ['country_code' => 'QA', 'year' => 2022, 'gdp' => 236000000000, 'inflation' => 5.0, 'currency_rate' => 3.64, 'risk_score' => 18],
            ['country_code' => 'QA', 'year' => 2023, 'gdp' => 220000000000, 'inflation' => 3.0, 'currency_rate' => 3.64, 'risk_score' => 21],
            ['country_code' => 'QA', 'year' => 2024, 'gdp' => 228000000000, 'inflation' => 2.8, 'currency_rate' => 3.64, 'risk_score' => 19],
            ['country_code' => 'QA', 'year' => 2025, 'gdp' => 236000000000, 'inflation' => 2.5, 'currency_rate' => 3.64, 'risk_score' => 18],
            ['country_code' => 'QA', 'year' => 2026, 'gdp' => 245000000000, 'inflation' => 2.2, 'currency_rate' => 3.64, 'risk_score' => 16],

            // Syria (SY)
            ['country_code' => 'SY', 'year' => 2020, 'gdp' => 16000000000, 'inflation' => 114.0, 'currency_rate' => 1250, 'risk_score' => 85],
            ['country_code' => 'SY', 'year' => 2021, 'gdp' => 14000000000, 'inflation' => 120.0, 'currency_rate' => 2500, 'risk_score' => 88],
            ['country_code' => 'SY', 'year' => 2022, 'gdp' => 12000000000, 'inflation' => 95.0, 'currency_rate' => 2500, 'risk_score' => 92],
            ['country_code' => 'SY', 'year' => 2023, 'gdp' => 12000000000, 'inflation' => 80.0, 'currency_rate' => 2500, 'risk_score' => 90],
            ['country_code' => 'SY', 'year' => 2024, 'gdp' => 11000000000, 'inflation' => 70.0, 'currency_rate' => 13000, 'risk_score' => 88],
            ['country_code' => 'SY', 'year' => 2025, 'gdp' => 11000000000, 'inflation' => 60.0, 'currency_rate' => 13000, 'risk_score' => 85],
            ['country_code' => 'SY', 'year' => 2026, 'gdp' => 12000000000, 'inflation' => 50.0, 'currency_rate' => 13000, 'risk_score' => 80],

            // Turkey (TR)
            ['country_code' => 'TR', 'year' => 2020, 'gdp' => 720000000000, 'inflation' => 12.3, 'currency_rate' => 7.02, 'risk_score' => 45],
            ['country_code' => 'TR', 'year' => 2021, 'gdp' => 819000000000, 'inflation' => 19.6, 'currency_rate' => 8.89, 'risk_score' => 48],
            ['country_code' => 'TR', 'year' => 2022, 'gdp' => 906000000000, 'inflation' => 72.3, 'currency_rate' => 16.57, 'risk_score' => 55],
            ['country_code' => 'TR', 'year' => 2023, 'gdp' => 1150000000000, 'inflation' => 53.9, 'currency_rate' => 23.80, 'risk_score' => 52],
            ['country_code' => 'TR', 'year' => 2024, 'gdp' => 1180000000000, 'inflation' => 50.0, 'currency_rate' => 32.20, 'risk_score' => 48],
            ['country_code' => 'TR', 'year' => 2025, 'gdp' => 1220000000000, 'inflation' => 38.0, 'currency_rate' => 34.50, 'risk_score' => 45],
            ['country_code' => 'TR', 'year' => 2026, 'gdp' => 1270000000000, 'inflation' => 28.0, 'currency_rate' => 36.00, 'risk_score' => 40],

            // United Arab Emirates (AE)
            ['country_code' => 'AE', 'year' => 2020, 'gdp' => 359000000000, 'inflation' => -2.1, 'currency_rate' => 3.67, 'risk_score' => 20],
            ['country_code' => 'AE', 'year' => 2021, 'gdp' => 415000000000, 'inflation' => 0.2, 'currency_rate' => 3.67, 'risk_score' => 18],
            ['country_code' => 'AE', 'year' => 2022, 'gdp' => 507000000000, 'inflation' => 4.8, 'currency_rate' => 3.67, 'risk_score' => 16],
            ['country_code' => 'AE', 'year' => 2023, 'gdp' => 500000000000, 'inflation' => 3.1, 'currency_rate' => 3.67, 'risk_score' => 19],
            ['country_code' => 'AE', 'year' => 2024, 'gdp' => 518000000000, 'inflation' => 2.3, 'currency_rate' => 3.67, 'risk_score' => 17],
            ['country_code' => 'AE', 'year' => 2025, 'gdp' => 538000000000, 'inflation' => 2.0, 'currency_rate' => 3.67, 'risk_score' => 16],
            ['country_code' => 'AE', 'year' => 2026, 'gdp' => 560000000000, 'inflation' => 1.8, 'currency_rate' => 3.67, 'risk_score' => 14],

            // Yemen (YE)
            ['country_code' => 'YE', 'year' => 2020, 'gdp' => 23000000000, 'inflation' => 26.2, 'currency_rate' => 250, 'risk_score' => 88],
            ['country_code' => 'YE', 'year' => 2021, 'gdp' => 21000000000, 'inflation' => 30.0, 'currency_rate' => 250, 'risk_score' => 90],
            ['country_code' => 'YE', 'year' => 2022, 'gdp' => 20000000000, 'inflation' => 25.0, 'currency_rate' => 250, 'risk_score' => 94],
            ['country_code' => 'YE', 'year' => 2023, 'gdp' => 21000000000, 'inflation' => 22.0, 'currency_rate' => 250, 'risk_score' => 92],
            ['country_code' => 'YE', 'year' => 2024, 'gdp' => 22000000000, 'inflation' => 20.0, 'currency_rate' => 250, 'risk_score' => 90],
            ['country_code' => 'YE', 'year' => 2025, 'gdp' => 23000000000, 'inflation' => 18.0, 'currency_rate' => 250, 'risk_score' => 87],
            ['country_code' => 'YE', 'year' => 2026, 'gdp' => 24000000000, 'inflation' => 15.0, 'currency_rate' => 250, 'risk_score' => 83],

            // Egypt (EG)
            ['country_code' => 'EG', 'year' => 2020, 'gdp' => 383000000000, 'inflation' => 5.0, 'currency_rate' => 15.70, 'risk_score' => 40],
            ['country_code' => 'EG', 'year' => 2021, 'gdp' => 424000000000, 'inflation' => 5.2, 'currency_rate' => 15.60, 'risk_score' => 38],
            ['country_code' => 'EG', 'year' => 2022, 'gdp' => 476000000000, 'inflation' => 13.9, 'currency_rate' => 19.10, 'risk_score' => 45],
            ['country_code' => 'EG', 'year' => 2023, 'gdp' => 395000000000, 'inflation' => 33.9, 'currency_rate' => 30.90, 'risk_score' => 48],
            ['country_code' => 'EG', 'year' => 2024, 'gdp' => 405000000000, 'inflation' => 25.0, 'currency_rate' => 47.50, 'risk_score' => 42],
            ['country_code' => 'EG', 'year' => 2025, 'gdp' => 420000000000, 'inflation' => 20.0, 'currency_rate' => 48.00, 'risk_score' => 39],
            ['country_code' => 'EG', 'year' => 2026, 'gdp' => 440000000000, 'inflation' => 15.0, 'currency_rate' => 47.00, 'risk_score' => 35],

            // Japan (JP)
            ['country_code' => 'JP', 'year' => 2020, 'gdp' => 5050000000000, 'inflation' => 0.0, 'currency_rate' => 106.8, 'risk_score' => 16],
            ['country_code' => 'JP', 'year' => 2021, 'gdp' => 5030000000000, 'inflation' => -0.2, 'currency_rate' => 109.8, 'risk_score' => 15],
            ['country_code' => 'JP', 'year' => 2022, 'gdp' => 4260000000000, 'inflation' => 2.5, 'currency_rate' => 131.5, 'risk_score' => 22],
            ['country_code' => 'JP', 'year' => 2023, 'gdp' => 4200000000000, 'inflation' => 3.2, 'currency_rate' => 140.5, 'risk_score' => 20],
            ['country_code' => 'JP', 'year' => 2024, 'gdp' => 4300000000000, 'inflation' => 2.2, 'currency_rate' => 150.2, 'risk_score' => 18],
            ['country_code' => 'JP', 'year' => 2025, 'gdp' => 4450000000000, 'inflation' => 2.0, 'currency_rate' => 148.0, 'risk_score' => 17],
            ['country_code' => 'JP', 'year' => 2026, 'gdp' => 4600000000000, 'inflation' => 1.8, 'currency_rate' => 145.0, 'risk_score' => 15],

            // South Korea (KR)
            ['country_code' => 'KR', 'year' => 2020, 'gdp' => 1640000000000, 'inflation' => 0.5, 'currency_rate' => 1180, 'risk_score' => 18],
            ['country_code' => 'KR', 'year' => 2021, 'gdp' => 1810000000000, 'inflation' => 2.5, 'currency_rate' => 1145, 'risk_score' => 17],
            ['country_code' => 'KR', 'year' => 2022, 'gdp' => 1670000000000, 'inflation' => 5.1, 'currency_rate' => 1290, 'risk_score' => 25],
            ['country_code' => 'KR', 'year' => 2023, 'gdp' => 1710000000000, 'inflation' => 3.6, 'currency_rate' => 1305, 'risk_score' => 22],
            ['country_code' => 'KR', 'year' => 2024, 'gdp' => 1780000000000, 'inflation' => 2.6, 'currency_rate' => 1350, 'risk_score' => 20],
            ['country_code' => 'KR', 'year' => 2025, 'gdp' => 1840000000000, 'inflation' => 2.4, 'currency_rate' => 1330, 'risk_score' => 18],
            ['country_code' => 'KR', 'year' => 2026, 'gdp' => 1910000000000, 'inflation' => 2.0, 'currency_rate' => 1310, 'risk_score' => 16],

            // India (IN)
            ['country_code' => 'IN', 'year' => 2020, 'gdp' => 2670000000000, 'inflation' => 6.6, 'currency_rate' => 74.1, 'risk_score' => 30],
            ['country_code' => 'IN', 'year' => 2021, 'gdp' => 3150000000000, 'inflation' => 5.1, 'currency_rate' => 73.9, 'risk_score' => 28],
            ['country_code' => 'IN', 'year' => 2022, 'gdp' => 3420000000000, 'inflation' => 6.7, 'currency_rate' => 78.6, 'risk_score' => 35],
            ['country_code' => 'IN', 'year' => 2023, 'gdp' => 3570000000000, 'inflation' => 5.7, 'currency_rate' => 82.2, 'risk_score' => 32],
            ['country_code' => 'IN', 'year' => 2024, 'gdp' => 3800000000000, 'inflation' => 5.1, 'currency_rate' => 83.1, 'risk_score' => 29],
            ['country_code' => 'IN', 'year' => 2025, 'gdp' => 4100000000000, 'inflation' => 4.5, 'currency_rate' => 82.8, 'risk_score' => 26],
            ['country_code' => 'IN', 'year' => 2026, 'gdp' => 4400000000000, 'inflation' => 4.0, 'currency_rate' => 82.0, 'risk_score' => 24],

            // Vietnam (VN)
            ['country_code' => 'VN', 'year' => 2020, 'gdp' => 346000000000, 'inflation' => 3.2, 'currency_rate' => 23200, 'risk_score' => 26],
            ['country_code' => 'VN', 'year' => 2021, 'gdp' => 366000000000, 'inflation' => 1.8, 'currency_rate' => 23000, 'risk_score' => 24],
            ['country_code' => 'VN', 'year' => 2022, 'gdp' => 408000000000, 'inflation' => 3.2, 'currency_rate' => 23400, 'risk_score' => 28],
            ['country_code' => 'VN', 'year' => 2023, 'gdp' => 430000000000, 'inflation' => 3.3, 'currency_rate' => 24300, 'risk_score' => 27],
            ['country_code' => 'VN', 'year' => 2024, 'gdp' => 450000000000, 'inflation' => 3.2, 'currency_rate' => 24800, 'risk_score' => 25],
            ['country_code' => 'VN', 'year' => 2025, 'gdp' => 480000000000, 'inflation' => 3.0, 'currency_rate' => 24600, 'risk_score' => 23],
            ['country_code' => 'VN', 'year' => 2026, 'gdp' => 510000000000, 'inflation' => 2.8, 'currency_rate' => 24400, 'risk_score' => 22],

            // Malaysia (MY)
            ['country_code' => 'MY', 'year' => 2020, 'gdp' => 337000000000, 'inflation' => -1.2, 'currency_rate' => 4.20, 'risk_score' => 20],
            ['country_code' => 'MY', 'year' => 2021, 'gdp' => 373000000000, 'inflation' => 2.5, 'currency_rate' => 4.14, 'risk_score' => 18],
            ['country_code' => 'MY', 'year' => 2022, 'gdp' => 407000000000, 'inflation' => 3.4, 'currency_rate' => 4.40, 'risk_score' => 22],
            ['country_code' => 'MY', 'year' => 2023, 'gdp' => 400000000000, 'inflation' => 2.5, 'currency_rate' => 4.56, 'risk_score' => 21],
            ['country_code' => 'MY', 'year' => 2024, 'gdp' => 420000000000, 'inflation' => 2.5, 'currency_rate' => 4.70, 'risk_score' => 19],
            ['country_code' => 'MY', 'year' => 2025, 'gdp' => 440000000000, 'inflation' => 2.2, 'currency_rate' => 4.60, 'risk_score' => 18],
            ['country_code' => 'MY', 'year' => 2026, 'gdp' => 460000000000, 'inflation' => 2.0, 'currency_rate' => 4.50, 'risk_score' => 16],
        ];

        foreach ($metrics as $m) {
            CountryMetric::updateOrCreate(
                ['country_code' => $m['country_code'], 'year' => $m['year']],
                $m
            );
        }
    }
}
