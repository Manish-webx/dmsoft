<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'location' => 'nullable|string|max:255',
            'message' => 'nullable|string',
            'remark' => 'nullable|string',
        ]);

        $lead = Lead::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Lead created successfully',
            'lead' => $lead,
        ], 201);
    }
}
