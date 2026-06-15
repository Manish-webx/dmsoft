<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Appointment;

class FollowupController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Appointment::with(['patient', 'doctor'])->where('status', 'Follow-up');
            
            if ($request->date_from) {
                $query->whereDate('appointment_date', '>=', $request->date_from);
            }
            if ($request->date_to) {
                $query->whereDate('appointment_date', '<=', $request->date_to);
            }
            
            return response()->json(['data' => $query->orderBy('appointment_date', 'asc')->get()]);
        }
        return view('followups.index');
    }
}
