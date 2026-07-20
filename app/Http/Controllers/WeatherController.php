<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function index(Request $request)
    {
        $countries = Country::orderBy('name')->get();
        $selectedCode = $request->input('country_code', 'DE'); // Default to Germany
        $selectedCountry = Country::where('code', $selectedCode)->first() ?? $countries->first();

        return view('weather.index', compact('countries', 'selectedCountry'));
    }
}
