<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('login');
});

use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ProblemController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/set-location', [App\Http\Controllers\LocationController::class, 'setLocation'])->name('set-location');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('patients/{patient}/restore', [PatientController::class, 'restore'])->name('patients.restore');
    Route::delete('patients/{patient}/force-delete', [PatientController::class, 'forceDelete'])->name('patients.force-delete');
    Route::resource('patients', PatientController::class);

    Route::post('appointments/{appointment}/restore', [AppointmentController::class, 'restore'])->name('appointments.restore');
    Route::delete('appointments/{appointment}/force-delete', [AppointmentController::class, 'forceDelete'])->name('appointments.force-delete');
    Route::resource('appointments', AppointmentController::class);

    Route::post('problems/{problem}/restore', [ProblemController::class, 'restore'])->name('problems.restore');
    Route::delete('problems/{problem}/force-delete', [ProblemController::class, 'forceDelete'])->name('problems.force-delete');
    Route::resource('problems', ProblemController::class);

    Route::get('prescriptions/{prescription}/pdf', [PrescriptionController::class, 'downloadPdf'])->name('prescriptions.pdf');
    Route::get('prescriptions/{prescription}/pdf/view', [PrescriptionController::class, 'viewPdf'])->name('prescriptions.viewPdf');
    Route::get('prescriptions/{prescription}/billing-pdf', [PrescriptionController::class, 'downloadBillingPdf'])->name('prescriptions.billingPdf');
    Route::get('prescriptions/{prescription}/billing-pdf/view', [PrescriptionController::class, 'viewBillingPdf'])->name('prescriptions.viewBillingPdf');
    Route::post('prescriptions/{prescription}/restore', [PrescriptionController::class, 'restore'])->name('prescriptions.restore');
    Route::delete('prescriptions/{prescription}/force-delete', [PrescriptionController::class, 'forceDelete'])->name('prescriptions.force-delete');
    Route::resource('prescriptions', PrescriptionController::class);

    Route::get('prescription-templates/all', [App\Http\Controllers\PrescriptionTemplateController::class, 'allTemplates'])->name('prescription-templates.all');
    Route::post('prescription-templates/{template}/duplicate', [App\Http\Controllers\PrescriptionTemplateController::class, 'duplicate'])->name('prescription-templates.duplicate');
    Route::resource('prescription-templates', App\Http\Controllers\PrescriptionTemplateController::class);

    Route::get('revenues', [App\Http\Controllers\RevenueController::class, 'index'])->name('revenues.index');

    Route::post('medicines/{medicine}/restore', [MedicineController::class, 'restore'])->name('medicines.restore');
    Route::delete('medicines/{medicine}/force-delete', [MedicineController::class, 'forceDelete'])->name('medicines.force-delete');
    Route::resource('medicines', MedicineController::class);
    Route::post('medicines/add-stock', [MedicineController::class, 'addStock'])->name('medicines.add-stock');
    Route::post('medicines/reduce-stock', [MedicineController::class, 'reduceStock'])->name('medicines.reduce-stock');

    Route::get('followups', [App\Http\Controllers\FollowupController::class, 'index'])->name('followups.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
