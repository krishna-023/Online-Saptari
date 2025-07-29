<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function index()
    {
        $businesses = Business::all();
        return view('business.index', compact('businesses'));
    }

    public function show($id)
    {
        $business = Business::findOrFail($id);
        return view('business.show', compact('business'));
    }
}
