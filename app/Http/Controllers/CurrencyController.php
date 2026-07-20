<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index(Request $request)
    {
        $countries = Country::where('currency_code', '!=', 'USD')->orderBy('name')->get();
        $selectedCode = $request->input('country_code', 'DE'); // Default to Germany
        $selectedCountry = Country::where('code', $selectedCode)->first() ?? $countries->first();

        return view('currency.index', compact('countries', 'selectedCountry'));
    }
}
