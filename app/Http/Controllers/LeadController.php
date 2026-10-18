<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Lead::orderBy('created_at', 'desc')->orderBy('id', 'desc');
            if ($request->has('trashed') && $request->trashed == '1') {
                $query->onlyTrashed();
            }
            return response()->json(['data' => $query->get()]);
        }
        return view('leads.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'location' => 'nullable|string|max:255',
            'message' => 'nullable|string',
            'remark' => 'nullable|string',
        ]);

        try {
            $data = $request->only('name', 'phone', 'email', 'location', 'message', 'remark');

            if ($request->filled('id')) {
                Lead::withTrashed()->findOrFail($request->id)->update($data);
                $message = 'Lead updated successfully.';
            } else {
                Lead::create($data);
                $message = 'Lead created successfully.';
            }

            return response()->json(['success' => $message]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred.',
                'errors' => [
                    'system_error' => ['Error: ' . $e->getMessage()]
                ]
            ], 422);
        }
    }

    public function edit($id)
    {
        $lead = Lead::withTrashed()->findOrFail($id);
        return response()->json($lead);
    }

    public function destroy($id)
    {
        Lead::findOrFail($id)->delete();
        return response()->json(['success' => 'Lead moved to trash.']);
    }

    public function restore($id)
    {
        Lead::withTrashed()->findOrFail($id)->restore();
        return response()->json(['success' => 'Lead restored successfully.']);
    }

    public function forceDelete($id)
    {
        Lead::withTrashed()->findOrFail($id)->forceDelete();
        return response()->json(['success' => 'Lead deleted permanently.']);
    }
}
