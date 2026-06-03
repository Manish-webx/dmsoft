<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ProblemController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('patients', PatientController::class);
    
    Route::resource('appointments', AppointmentController::class);

    Route::resource('problems', ProblemController::class);
    
    Route::get('prescriptions/{prescription}/pdf', [PrescriptionController::class, 'downloadPdf'])->name('prescriptions.pdf');
    Route::get('prescriptions/{prescription}/pdf/view', [PrescriptionController::class, 'viewPdf'])->name('prescriptions.viewPdf');
    Route::resource('prescriptions', PrescriptionController::class);
    Route::get('revenues', [App\Http\Controllers\RevenueController::class, 'index'])->name('revenues.index');
    
    Route::resource('medicines', MedicineController::class);
    Route::post('medicines/add-stock', [MedicineController::class, 'addStock'])->name('medicines.add-stock');
    
    Route::get('followups', [App\Http\Controllers\FollowupController::class, 'index'])->name('followups.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
