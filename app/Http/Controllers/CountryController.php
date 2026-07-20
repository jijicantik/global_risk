<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\RiskScore;
use App\Services\RiskEngine;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    protected $riskEngine;

    public function __construct(RiskEngine $riskEngine)
    {
        $this->riskEngine = $riskEngine;
    }

    public function index()
    {
        $countries = Country::with('riskScore')->get();
        return view('country.index', compact('countries'));
    }

    public function show($code)
    {
        $country = Country::with(['metrics', 'riskScore'])->where('code', strtoupper($code))->firstOrFail();
        
        // Dynamically compute risk score if missing
        if (!$country->riskScore) {
            $this->riskEngine->calculateCountryRisk($country);
            $country->load('riskScore');
        }

        // Get watchlist status
        $isWatchlisted = auth()->check() 
            ? auth()->user()->watchlists()->where('country_code', $country->code)->exists()
            : false;

        return view('country.show', compact('country', 'isWatchlisted'));
    }

    public function compare(Request $request)
    {
        $countries = Country::orderBy('name')->get();
        $countryA = null;
        $countryB = null;

        if ($request->has('country_a') && $request->has('country_b')) {
            $countryA = Country::with(['metrics', 'riskScore'])->where('code', $request->input('country_a'))->first();
            $countryB = Country::with(['metrics', 'riskScore'])->where('code', $request->input('country_b'))->first();

            if ($countryA && !$countryA->riskScore) {
                $this->riskEngine->calculateCountryRisk($countryA);
                $countryA->load('riskScore');
            }
            if ($countryB && !$countryB->riskScore) {
                $this->riskEngine->calculateCountryRisk($countryB);
                $countryB->load('riskScore');
            }
        }

        return view('country.compare', compact('countries', 'countryA', 'countryB'));
    }
}
