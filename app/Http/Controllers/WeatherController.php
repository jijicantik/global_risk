<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function index(Request $request)
    {
        $countries = Country::with('ports')->orderBy('name')->get();
        $selectedCode = $request->input('country_code', 'ID');
        $selectedCountry = Country::with('ports')->where('code', $selectedCode)->first() ?? $countries->first();

        return view('weather.index', compact('countries', 'selectedCountry'));
    }
}
