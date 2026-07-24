<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Patient;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Patient::latest();
            if ($request->has('trashed') && $request->trashed) {
                $query->onlyTrashed();
            }
            return response()->json(['data' => $query->get()]);
        }
        return view('patients.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|string',
            'dob' => 'nullable|string',
            'address' => 'nullable|string',
            'source' => 'nullable|string|max:255',
        ]);

        $data = $request->only('name', 'phone', 'gender', 'dob', 'address', 'notes', 'source');
        
        if ($request->filled('id')) {
            Patient::findOrFail($request->id)->update($data);
        } else {
            Patient::create($data);
        }

        return response()->json(['success' => 'Patient saved successfully.']);
    }

    public function edit($id)
    {
        $patient = Patient::findOrFail($id);
        return response()->json($patient);
    }

    public function destroy($id)
    {
        Patient::findOrFail($id)->delete();
        return response()->json(['success' => 'Patient moved to trash.']);
    }

    public function restore($id)
    {
        Patient::withTrashed()->findOrFail($id)->restore();
        return response()->json(['success' => 'Patient restored successfully.']);
    }

    public function forceDelete($id)
    {
        Patient::withTrashed()->findOrFail($id)->forceDelete();
        return response()->json(['success' => 'Patient deleted permanently.']);
    }
}
