<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // DataTable request
            $query = Appointment::with(['patient', 'doctor'])->latest();
            
            if ($request->has('trashed') && $request->trashed) {
                $query->onlyTrashed();
            } else {
                if ($request->has('date_from') && !empty($request->date_from)) {
                    $query->whereDate('appointment_date', '>=', $request->date_from);
                } else {
                    // Default to today if no filter
                    $query->whereDate('appointment_date', '>=', now()->toDateString());
                }

                if ($request->has('date_to') && !empty($request->date_to)) {
                    $query->whereDate('appointment_date', '<=', $request->date_to);
                } else {
                    // Default to today if no filter
                    $query->whereDate('appointment_date', '<=', now()->toDateString());
                }
            }
            
            $appointments = $query->get();
            return response()->json(['data' => $appointments]);
        }
        
        $patients = Patient::all();
        $doctors = User::role('Doctor')->get(); // Spatie scope
        
        return view('appointments.index', compact('patients', 'doctors'));
    }

    private function getStatusColor($status) {
        return match($status) {
            'Confirmed' => 'bg-primary',
            'Visited' => 'bg-success',
            'Follow-up' => 'bg-warning text-dark',
            'No Show' => 'bg-danger',
            default => 'bg-info',
        };
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        Appointment::updateOrCreate(
            ['id' => $request->id],
            $request->only('patient_id', 'doctor_id', 'appointment_date', 'status', 'notes', 'followup_date')
        );

        return response()->json(['success' => 'Appointment saved successfully.']);
    }

    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        return response()->json($appointment);
    }

    public function destroy($id)
    {
        Appointment::findOrFail($id)->delete();
        return response()->json(['success' => 'Appointment moved to trash.']);
    }

    public function restore($id)
    {
        Appointment::withTrashed()->findOrFail($id)->restore();
        return response()->json(['success' => 'Appointment restored successfully.']);
    }

    public function forceDelete($id)
    {
        Appointment::withTrashed()->findOrFail($id)->forceDelete();
        return response()->json(['success' => 'Appointment deleted permanently.']);
    }
}
