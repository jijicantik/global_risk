<?php

namespace Database\Seeders;

use App\Models\Port;
use Illuminate\Database\Seeder;

class PortSeeder extends Seeder
{
    public function run(): void
    {
        $ports = [
            // --- GERMANY (DE) ---
            [
                'name' => 'Port of Rotterdam',
                'code' => 'NLRTM',
                'latitude' => 51.924420,
                'longitude' => 4.477733,
                'country_code' => 'NL',
                'country_name' => 'Netherlands',
            ],
            [
                'name' => 'Port of Amsterdam',
                'code' => 'NLAMS',
                'latitude' => 52.367600,
                'longitude' => 4.904100,
                'country_code' => 'NL',
                'country_name' => 'Netherlands',
            ],
            [
                'name' => 'Port of Flushing (Vlissingen)',
                'code' => 'NLVLI',
                'latitude' => 51.442500,
                'longitude' => 3.573900,
                'country_code' => 'NL',
                'country_name' => 'Netherlands',
            ],

            // --- GERMANY (DE) ---
            [
                'name' => 'Port of Hamburg',
                'code' => 'DEHAM',
                'latitude' => 53.551086,
                'longitude' => 9.993682,
                'country_code' => 'DE',
                'country_name' => 'Germany',
            ],
            [
                'name' => 'Port of Bremerhaven',
                'code' => 'DEBRV',
                'latitude' => 53.548900,
                'longitude' => 8.577200,
                'country_code' => 'DE',
                'country_name' => 'Germany',
            ],
            [
                'name' => 'Port of Wilhelmshaven',
                'code' => 'DEWVN',
                'latitude' => 53.516700,
                'longitude' => 8.133300,
                'country_code' => 'DE',
                'country_name' => 'Germany',
            ],
            [
                'name' => 'Port of Rostock',
                'code' => 'DEROS',
                'latitude' => 54.148100,
                'longitude' => 12.100800,
                'country_code' => 'DE',
                'country_name' => 'Germany',
            ],

            // --- CHINA (CN) ---
            [
                'name' => 'Port of Shanghai',
                'code' => 'CNSHA',
                'latitude' => 31.230416,
                'longitude' => 121.473701,
                'country_code' => 'CN',
                'country_name' => 'China',
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
            [
                'name' => 'Port of Guangzhou',
                'code' => 'CNCAN',
                'latitude' => 23.129100,
                'longitude' => 113.264400,
                'country_code' => 'CN',
                'country_name' => 'China',
            ],
            [
                'name' => 'Port of Qingdao',
                'code' => 'CNTAO',
                'latitude' => 36.067100,
                'longitude' => 120.382600,
                'country_code' => 'CN',
                'country_name' => 'China',
            ],
            [
                'name' => 'Port of Tianjin',
                'code' => 'CNTXG',
                'latitude' => 38.985800,
                'longitude' => 117.742800,
                'country_code' => 'CN',
                'country_name' => 'China',
            ],
            [
                'name' => 'Port of Dalian',
                'code' => 'CNDLN',
                'latitude' => 38.914000,
                'longitude' => 121.614700,
                'country_code' => 'CN',
                'country_name' => 'China',
            ],
            [
                'name' => 'Port of Xiamen',
                'code' => 'CNXMN',
                'latitude' => 24.479800,
                'longitude' => 118.089400,
                'country_code' => 'CN',
                'country_name' => 'China',
            ],

            // --- SINGAPORE (SG) ---
            [
                'name' => 'Port of Singapore',
                'code' => 'SGSIN',
                'latitude' => 1.264000,
                'longitude' => 103.840000,
                'country_code' => 'SG',
                'country_name' => 'Singapore',
            ],
            [
                'name' => 'Jurong Port',
                'code' => 'SGJUR',
                'latitude' => 1.300000,
                'longitude' => 103.715000,
                'country_code' => 'SG',
                'country_name' => 'Singapore',
            ],
            [
                'name' => 'Pasir Panjang Terminal',
                'code' => 'SGPPT',
                'latitude' => 1.275000,
                'longitude' => 103.785000,
                'country_code' => 'SG',
                'country_name' => 'Singapore',
            ],

            // --- INDONESIA (ID) ---
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
                'name' => 'Port of Belawan (Medan)',
                'code' => 'IDBLW',
                'latitude' => 3.784400,
                'longitude' => 98.683300,
                'country_code' => 'ID',
                'country_name' => 'Indonesia',
            ],
            [
                'name' => 'Port of Makassar (Soekarno-Hatta)',
                'code' => 'IDMAK',
                'latitude' => -5.133300,
                'longitude' => 119.408300,
                'country_code' => 'ID',
                'country_name' => 'Indonesia',
            ],
            [
                'name' => 'Port of Batam',
                'code' => 'IDBTM',
                'latitude' => 1.130400,
                'longitude' => 104.053000,
                'country_code' => 'ID',
                'country_name' => 'Indonesia',
            ],
            [
                'name' => 'Port of Tanjung Emas (Semarang)',
                'code' => 'IDSRG',
                'latitude' => -6.953600,
                'longitude' => 110.426700,
                'country_code' => 'ID',
                'country_name' => 'Indonesia',
            ],
            [
                'name' => 'Port of Bitung',
                'code' => 'IDBIT',
                'latitude' => 1.442500,
                'longitude' => 125.191100,
                'country_code' => 'ID',
                'country_name' => 'Indonesia',
            ],
            [
                'name' => 'Port of Teluk Bayur (Padang)',
                'code' => 'IDPDG',
                'latitude' => -0.998600,
                'longitude' => 100.370300,
                'country_code' => 'ID',
                'country_name' => 'Indonesia',
            ],

            // --- UNITED STATES (US) ---
            [
                'name' => 'Port of Los Angeles',
                'code' => 'USLAX',
                'latitude' => 33.740000,
                'longitude' => -118.260000,
                'country_code' => 'US',
                'country_name' => 'United States',
            ],
            [
                'name' => 'Port of Long Beach',
                'code' => 'USLGB',
                'latitude' => 33.770100,
                'longitude' => -118.193700,
                'country_code' => 'US',
                'country_name' => 'United States',
            ],
            [
                'name' => 'Port of New York & New Jersey',
                'code' => 'USNYC',
                'latitude' => 40.668600,
                'longitude' => -74.120600,
                'country_code' => 'US',
                'country_name' => 'United States',
            ],
            [
                'name' => 'Port of Savannah',
                'code' => 'USSAV',
                'latitude' => 32.080900,
                'longitude' => -81.091200,
                'country_code' => 'US',
                'country_name' => 'United States',
            ],
            [
                'name' => 'Port of Houston',
                'code' => 'USHOU',
                'latitude' => 29.760400,
                'longitude' => -95.369800,
                'country_code' => 'US',
                'country_name' => 'United States',
            ],
            [
                'name' => 'Port of Seattle',
                'code' => 'USSEA',
                'latitude' => 47.606200,
                'longitude' => -122.332100,
                'country_code' => 'US',
                'country_name' => 'United States',
            ],

            // --- AUSTRALIA (AU) ---
            [
                'name' => 'Port of Sydney',
                'code' => 'AUSYD',
                'latitude' => -33.868820,
                'longitude' => 151.209296,
                'country_code' => 'AU',
                'country_name' => 'Australia',
            ],
            [
                'name' => 'Port of Melbourne',
                'code' => 'AUMEL',
                'latitude' => -37.813600,
                'longitude' => 144.963100,
                'country_code' => 'AU',
                'country_name' => 'Australia',
            ],
            [
                'name' => 'Port of Brisbane',
                'code' => 'AUBNE',
                'latitude' => -27.469800,
                'longitude' => 153.025100,
                'country_code' => 'AU',
                'country_name' => 'Australia',
            ],
            [
                'name' => 'Port of Fremantle (Perth)',
                'code' => 'AUFRE',
                'latitude' => -32.056900,
                'longitude' => 115.742800,
                'country_code' => 'AU',
                'country_name' => 'Australia',
            ],
            [
                'name' => 'Port Hedland',
                'code' => 'AUPHE',
                'latitude' => -20.310800,
                'longitude' => 118.575300,
                'country_code' => 'AU',
                'country_name' => 'Australia',
            ],

            // --- JAPAN (JP) ---
            [
                'name' => 'Port of Tokyo',
                'code' => 'JPTYO',
                'latitude' => 35.617900,
                'longitude' => 139.776600,
                'country_code' => 'JP',
                'country_name' => 'Japan',
            ],
            [
                'name' => 'Port of Yokohama',
                'code' => 'JPYOK',
                'latitude' => 35.443700,
                'longitude' => 139.638000,
                'country_code' => 'JP',
                'country_name' => 'Japan',
            ],
            [
                'name' => 'Port of Kobe',
                'code' => 'JPUKB',
                'latitude' => 34.683300,
                'longitude' => 135.200000,
                'country_code' => 'JP',
                'country_name' => 'Japan',
            ],
            [
                'name' => 'Port of Nagoya',
                'code' => 'JPNGO',
                'latitude' => 35.083300,
                'longitude' => 136.883300,
                'country_code' => 'JP',
                'country_name' => 'Japan',
            ],

            // --- SOUTH KOREA (KR) ---
            [
                'name' => 'Port of Busan',
                'code' => 'KRPUS',
                'latitude' => 35.101700,
                'longitude' => 129.030000,
                'country_code' => 'KR',
                'country_name' => 'South Korea',
            ],
            [
                'name' => 'Port of Incheon',
                'code' => 'KRINC',
                'latitude' => 37.456300,
                'longitude' => 126.632500,
                'country_code' => 'KR',
                'country_name' => 'South Korea',
            ],
            [
                'name' => 'Port of Gwangyang',
                'code' => 'KRGWY',
                'latitude' => 34.908300,
                'longitude' => 127.701900,
                'country_code' => 'KR',
                'country_name' => 'South Korea',
            ],

            // --- INDIA (IN) ---
            [
                'name' => 'Port of Nhava Sheva (Mumbai)',
                'code' => 'INNSA',
                'latitude' => 18.950000,
                'longitude' => 72.950000,
                'country_code' => 'IN',
                'country_name' => 'India',
            ],
            [
                'name' => 'Mundra Port',
                'code' => 'INMUN',
                'latitude' => 22.740000,
                'longitude' => 69.700000,
                'country_code' => 'IN',
                'country_name' => 'India',
            ],
            [
                'name' => 'Port of Chennai',
                'code' => 'INMAA',
                'latitude' => 13.083300,
                'longitude' => 80.300000,
                'country_code' => 'IN',
                'country_name' => 'India',
            ],
            [
                'name' => 'Port of Cochin',
                'code' => 'INCOK',
                'latitude' => 9.966700,
                'longitude' => 76.266700,
                'country_code' => 'IN',
                'country_name' => 'India',
            ],

            // --- UNITED ARAB EMIRATES (AE) ---
            [
                'name' => 'Port of Jebel Ali',
                'code' => 'AEJEA',
                'latitude' => 24.983900,
                'longitude' => 55.076300,
                'country_code' => 'AE',
                'country_name' => 'United Arab Emirates',
            ],
            [
                'name' => 'Port of Fujairah',
                'code' => 'AEFJR',
                'latitude' => 25.176400,
                'longitude' => 56.358100,
                'country_code' => 'AE',
                'country_name' => 'United Arab Emirates',
            ],
            [
                'name' => 'Khalifa Port (Abu Dhabi)',
                'code' => 'AEKHA',
                'latitude' => 24.810800,
                'longitude' => 54.654700,
                'country_code' => 'AE',
                'country_name' => 'United Arab Emirates',
            ],

            // --- SAUDI ARABIA (SA) ---
            [
                'name' => 'Port of Jeddah',
                'code' => 'SAJED',
                'latitude' => 21.483300,
                'longitude' => 39.183300,
                'country_code' => 'SA',
                'country_name' => 'Saudi Arabia',
            ],
            [
                'name' => 'King Abdulaziz Port (Dammam)',
                'code' => 'SADMM',
                'latitude' => 26.433300,
                'longitude' => 50.100000,
                'country_code' => 'SA',
                'country_name' => 'Saudi Arabia',
            ],
            [
                'name' => 'King Abdullah Port',
                'code' => 'SAKAP',
                'latitude' => 22.500000,
                'longitude' => 39.000000,
                'country_code' => 'SA',
                'country_name' => 'Saudi Arabia',
            ],

            // --- BAHRAIN (BH) ---
            [
                'name' => 'Khalifa Bin Salman Port',
                'code' => 'BHKBS',
                'latitude' => 26.196900,
                'longitude' => 50.686900,
                'country_code' => 'BH',
                'country_name' => 'Bahrain',
            ],
            [
                'name' => 'Mina Sulman Port',
                'code' => 'BHMSU',
                'latitude' => 26.208300,
                'longitude' => 50.612500,
                'country_code' => 'BH',
                'country_name' => 'Bahrain',
            ],

            // --- ISRAEL (IL) ---
            [
                'name' => 'Port of Haifa',
                'code' => 'ILHFA',
                'latitude' => 32.819700,
                'longitude' => 34.998300,
                'country_code' => 'IL',
                'country_name' => 'Israel',
            ],
            [
                'name' => 'Port of Ashdod',
                'code' => 'ILASH',
                'latitude' => 31.828600,
                'longitude' => 34.646900,
                'country_code' => 'IL',
                'country_name' => 'Israel',
            ],

            // --- OMAN (OM) ---
            [
                'name' => 'Port of Salalah',
                'code' => 'OMSLL',
                'latitude' => 16.950000,
                'longitude' => 54.008300,
                'country_code' => 'OM',
                'country_name' => 'Oman',
            ],
            [
                'name' => 'Port of Sohar',
                'code' => 'OMSOH',
                'latitude' => 24.363300,
                'longitude' => 56.633300,
                'country_code' => 'OM',
                'country_name' => 'Oman',
            ],
            [
                'name' => 'Port of Duqm',
                'code' => 'OMDQM',
                'latitude' => 19.664400,
                'longitude' => 57.702800,
                'country_code' => 'OM',
                'country_name' => 'Oman',
            ],

            // --- QATAR (QA) ---
            [
                'name' => 'Hamad Port',
                'code' => 'QAQAT',
                'latitude' => 25.021000,
                'longitude' => 51.611000,
                'country_code' => 'QA',
                'country_name' => 'Qatar',
            ],
            [
                'name' => 'Port of Ras Laffan',
                'code' => 'QARLF',
                'latitude' => 25.918900,
                'longitude' => 51.581900,
                'country_code' => 'QA',
                'country_name' => 'Qatar',
            ],

            // --- TURKEY (TR) ---
            [
                'name' => 'Port of Ambarli (Istanbul)',
                'code' => 'TRAMB',
                'latitude' => 40.970000,
                'longitude' => 28.680000,
                'country_code' => 'TR',
                'country_name' => 'Turkey',
            ],
            [
                'name' => 'Port of Mersin',
                'code' => 'TRMER',
                'latitude' => 36.800000,
                'longitude' => 34.633300,
                'country_code' => 'TR',
                'country_name' => 'Turkey',
            ],
            [
                'name' => 'Port of Izmir',
                'code' => 'TRIZM',
                'latitude' => 38.433300,
                'longitude' => 27.150000,
                'country_code' => 'TR',
                'country_name' => 'Turkey',
            ],

            // --- EGYPT (EG) ---
            [
                'name' => 'Port of Alexandria',
                'code' => 'EGALY',
                'latitude' => 31.200000,
                'longitude' => 29.883300,
                'country_code' => 'EG',
                'country_name' => 'Egypt',
            ],
            [
                'name' => 'Port Said',
                'code' => 'EGPSD',
                'latitude' => 31.256700,
                'longitude' => 32.289700,
                'country_code' => 'EG',
                'country_name' => 'Egypt',
            ],
            [
                'name' => 'Damietta Port',
                'code' => 'EGDAM',
                'latitude' => 31.462800,
                'longitude' => 31.750800,
                'country_code' => 'EG',
                'country_name' => 'Egypt',
            ],

            // --- MALAYSIA (MY) ---
            [
                'name' => 'Port Klang',
                'code' => 'MYPKG',
                'latitude' => 3.000000,
                'longitude' => 101.383300,
                'country_code' => 'MY',
                'country_name' => 'Malaysia',
            ],
            [
                'name' => 'Port of Tanjung Pelepas',
                'code' => 'MYTPP',
                'latitude' => 1.365300,
                'longitude' => 103.547500,
                'country_code' => 'MY',
                'country_name' => 'Malaysia',
            ],
            [
                'name' => 'Penang Port',
                'code' => 'MYPEN',
                'latitude' => 5.416700,
                'longitude' => 100.350000,
                'country_code' => 'MY',
                'country_name' => 'Malaysia',
            ],
            [
                'name' => 'Johor Port (Pasir Gudang)',
                'code' => 'MYJOH',
                'latitude' => 1.433300,
                'longitude' => 103.900000,
                'country_code' => 'MY',
                'country_name' => 'Malaysia',
            ],

            // --- VIETNAM (VN) ---
            [
                'name' => 'Port of Ho Chi Minh City (Cat Lai)',
                'code' => 'VNSGN',
                'latitude' => 10.760000,
                'longitude' => 106.790000,
                'country_code' => 'VN',
                'country_name' => 'Vietnam',
            ],
            [
                'name' => 'Port of Haiphong',
                'code' => 'VNHPH',
                'latitude' => 20.865000,
                'longitude' => 106.683300,
                'country_code' => 'VN',
                'country_name' => 'Vietnam',
            ],
            [
                'name' => 'Port of Da Nang',
                'code' => 'VNDAD',
                'latitude' => 16.068000,
                'longitude' => 108.224000,
                'country_code' => 'VN',
                'country_name' => 'Vietnam',
            ],
            [
                'name' => 'Cai Mep International Terminal',
                'code' => 'VNCME',
                'latitude' => 10.528300,
                'longitude' => 107.025000,
                'country_code' => 'VN',
                'country_name' => 'Vietnam',
            ],

            // --- IRAN (IR) ---
            [
                'name' => 'Port of Bandar Abbas',
                'code' => 'IRBND',
                'latitude' => 27.133300,
                'longitude' => 56.200000,
                'country_code' => 'IR',
                'country_name' => 'Iran',
            ],
            [
                'name' => 'Port of Chabahar',
                'code' => 'IRZBR',
                'latitude' => 25.296900,
                'longitude' => 60.643600,
                'country_code' => 'IR',
                'country_name' => 'Iran',
            ],
            [
                'name' => 'Port of Bushehr',
                'code' => 'IRBUZ',
                'latitude' => 28.968300,
                'longitude' => 50.839400,
                'country_code' => 'IR',
                'country_name' => 'Iran',
            ],

            // --- IRAQ (IQ) ---
            [
                'name' => 'Port of Umm Qasr',
                'code' => 'IRQUM',
                'latitude' => 30.038300,
                'longitude' => 47.925800,
                'country_code' => 'IQ',
                'country_name' => 'Iraq',
            ],
            [
                'name' => 'Port of Khor Al Zubair',
                'code' => 'IQKAZ',
                'latitude' => 30.194400,
                'longitude' => 47.886400,
                'country_code' => 'IQ',
                'country_name' => 'Iraq',
            ],

            // --- KUWAIT (KW) ---
            [
                'name' => 'Port of Shuwaikh',
                'code' => 'KWSWK',
                'latitude' => 29.351400,
                'longitude' => 47.927500,
                'country_code' => 'KW',
                'country_name' => 'Kuwait',
            ],
            [
                'name' => 'Port of Shuaiba',
                'code' => 'KWSAA',
                'latitude' => 29.043100,
                'longitude' => 48.150000,
                'country_code' => 'KW',
                'country_name' => 'Kuwait',
            ],

            // --- LEBANON (LB) ---
            [
                'name' => 'Port of Beirut',
                'code' => 'LBBEY',
                'latitude' => 33.901700,
                'longitude' => 35.518600,
                'country_code' => 'LB',
                'country_name' => 'Lebanon',
            ],
            [
                'name' => 'Port of Tripoli',
                'code' => 'LBKYE',
                'latitude' => 34.450000,
                'longitude' => 35.816700,
                'country_code' => 'LB',
                'country_name' => 'Lebanon',
            ],

            // --- PALESTINE (PS) ---
            [
                'name' => 'Port of Gaza',
                'code' => 'PSGZA',
                'latitude' => 31.524400,
                'longitude' => 34.437200,
                'country_code' => 'PS',
                'country_name' => 'Palestine',
            ],

            // --- SYRIA (SY) ---
            [
                'name' => 'Port of Lattakia',
                'code' => 'SYLTK',
                'latitude' => 35.531700,
                'longitude' => 35.771100,
                'country_code' => 'SY',
                'country_name' => 'Syria',
            ],
            [
                'name' => 'Port of Tartous',
                'code' => 'SYTTU',
                'latitude' => 34.888900,
                'longitude' => 35.886700,
                'country_code' => 'SY',
                'country_name' => 'Syria',
            ],

            // --- YEMEN (YE) ---
            [
                'name' => 'Port of Aden',
                'code' => 'YEADE',
                'latitude' => 12.783300,
                'longitude' => 44.966700,
                'country_code' => 'YE',
                'country_name' => 'Yemen',
            ],
            [
                'name' => 'Port of Hodeidah',
                'code' => 'YEHOD',
                'latitude' => 14.797800,
                'longitude' => 42.948300,
                'country_code' => 'YE',
                'country_name' => 'Yemen',
            ],
        ];

        foreach ($ports as $p) {
            Port::updateOrCreate(['code' => $p['code']], $p);
        }
    }
}
