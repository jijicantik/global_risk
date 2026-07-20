<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Watchlist;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $watchlistCodes = $user->watchlists()->pluck('country_code')->toArray();
        $countries = Country::with('riskScore')
            ->whereIn('code', $watchlistCodes)
            ->get();

        return view('watchlist.index', compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'country_code' => 'required|string|exists:countries,code'
        ]);

        Watchlist::updateOrCreate([
            'user_id' => auth()->id(),
            'country_code' => strtoupper($request->country_code)
        ]);

        return redirect()->back()->with('success', 'Country added to watchlist.');
    }

    public function destroy($code)
    {
        Watchlist::where([
            'user_id' => auth()->id(),
            'country_code' => strtoupper($code)
        ])->delete();

        return redirect()->back()->with('success', 'Country removed from watchlist.');
    }
}
