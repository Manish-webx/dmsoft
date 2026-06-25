<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function setLocation(Request $request)
    {
        $request->validate([
            'location' => 'required|string|in:Gurgaon,Patel Nagar'
        ]);

        session(['current_location' => $request->location]);

        return redirect()->back()->with('success', 'Location switched to ' . $request->location);
    }
}
