<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Medicine;
use App\Models\Prescription;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        
        $todaysAppointments = Appointment::whereDate('appointment_date', $today)->count();
        $todaysFollowups = Appointment::whereDate('appointment_date', $today)->where('status', 'Follow-up')->count();
        $totalPatients = Patient::count();
        $lowStockMedicines = Medicine::where('stock', '<', 10)->where('status', 1)->count();

        $recentAppointments = Appointment::with(['patient', 'doctor'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $recentPrescriptions = Prescription::with(['patient', 'doctor'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'todaysAppointments', 
            'todaysFollowups', 
            'totalPatients', 
            'lowStockMedicines',
            'recentAppointments',
            'recentPrescriptions'
        ));
    }
}
