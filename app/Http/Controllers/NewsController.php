<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\NewsCache;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $countries = Country::orderBy('name')->get();
        $selectedCode = $request->input('country_code', 'DE'); // Default to Germany
        $selectedCountry = Country::where('code', $selectedCode)->first() ?? $countries->first();

        return view('news.index', compact('countries', 'selectedCountry'));
    }
}
