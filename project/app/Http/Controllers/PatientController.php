<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Patient;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $patients = Patient::latest()->get();
            return response()->json(['data' => $patients]);
        }
        return view('patients.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|string',
            'dob' => 'nullable|date',
            'address' => 'nullable|string',
        ]);

        Patient::updateOrCreate(
            ['id' => $request->id],
            $request->only('name', 'phone', 'gender', 'dob', 'address', 'notes')
        );

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
        return response()->json(['success' => 'Patient deleted successfully.']);
    }
}
