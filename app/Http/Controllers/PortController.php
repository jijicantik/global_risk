<?php

namespace App\Http\Controllers;

use App\Models\Port;
use Illuminate\Http\Request;

class PortController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $ports = Port::when($search, function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('country_name', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%");
        })->get();

        return view('ports.index', compact('ports', 'search'));
    }
}
