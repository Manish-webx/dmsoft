<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Medicine;
use App\Models\MedicineTransaction;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Revenue;
use App\Models\Problem;
use Illuminate\Support\Facades\DB;

class PrescriptionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $prescriptions = Prescription::with(['patient', 'doctor', 'problem'])->latest()->get();
            return response()->json(['data' => $prescriptions]);
        }
        return view('prescriptions.index');
    }

    public function create()
    {
        $patients = Patient::all();
        $medicines = Medicine::where('status', 1)->get(); // Need all for alternate medicine selection
        $problems = Problem::all();
        return view('prescriptions.create', compact('patients', 'medicines', 'problems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medicines' => 'required|array|min:1',
            'medicines.*' => 'required|exists:medicines,id',
            'alternate_medicines' => 'nullable|array',
            'quantities' => 'required|array|min:1',
            'timings' => 'required|array',
            'durations' => 'required|array',
            'instructions' => 'required|array',
            'medicine_notes' => 'nullable|array',
            'discount_type' => 'nullable|string|in:flat,percentage',
            'discount_value' => 'nullable|numeric|min:0',
            'received_amount' => 'nullable|numeric|min:0',
            'payment_mode' => 'nullable|string',
            'problem_id' => 'nullable|exists:problems,id',
            'youtube_links' => 'nullable|array',
            'hopi' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $prescription = Prescription::create([
                'patient_id' => $request->patient_id,
                'doctor_id' => auth()->id(),
                'date' => now()->toDateString(),
                'next_followup_date' => $request->next_followup_date,
                'notes' => $request->notes,
                'problem_id' => $request->problem_id,
                'youtube_links' => $request->youtube_links,
                'hopi' => $request->hopi,
            ]);

            $subtotal = 0;

            foreach ($request->medicines as $key => $medicine_id) {
                $quantity = $request->quantities[$key];
                $medicine = Medicine::findOrFail($medicine_id);
                
                $subtotal += ($medicine->price * $quantity);

                if ($medicine->stock < $quantity) {
                    throw new \Exception("Insufficient stock for {$medicine->name}. Available: {$medicine->stock}");
                }

                // Create Item
                PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'medicine_id' => $medicine_id,
                    'alternate_medicine_id' => $request->alternate_medicines[$key] ?? null,
                    'timing' => $request->timings[$key] ?? null,
                    'duration' => $request->durations[$key] ?? null,
                    'quantity' => $quantity,
                    'instructions' => $request->instructions[$key] ?? null,
                    'medicine_note' => $request->medicine_notes[$key] ?? null,
                ]);

                // Deduct Stock
                $medicine->decrement('stock', $quantity);

                // Create Transaction
                MedicineTransaction::create([
                    'medicine_id' => $medicine_id,
                    'type' => 'OUT',
                    'quantity' => $quantity,
                    'reference_type' => 'Prescription',
                    'reference_id' => $prescription->id,
                    'created_by' => auth()->id(),
                ]);
            }

            // Calculate final amount
            $discount_value = $request->discount_value ?? 0;
            $finalAmount = $subtotal;
            if ($request->discount_type == 'percentage') {
                $finalAmount = $subtotal - ($subtotal * ($discount_value / 100));
            } elseif ($request->discount_type == 'flat') {
                $finalAmount = $subtotal - $discount_value;
            }

            $received = $request->received_amount !== null ? (float)$request->received_amount : $finalAmount;
            $due = $finalAmount - $received;

            // Create Revenue record
            Revenue::create([
                'patient_id' => $prescription->patient_id,
                'prescription_id' => $prescription->id,
                'subtotal' => $subtotal,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount_value,
                'final_amount' => $finalAmount,
                'received_amount' => $received,
                'due_amount' => $due,
                'payment_mode' => $request->payment_mode,
                'created_by' => auth()->id()
            ]);

            DB::commit();

            // If follow-up date is provided, create an appointment
            if ($request->next_followup_date) {
                Appointment::create([
                    'patient_id' => $request->patient_id,
                    'doctor_id' => auth()->id(),
                    'appointment_date' => $request->next_followup_date . ' 10:00:00', // default time
                    'status' => 'Follow-up',
                    'notes' => 'Generated from Prescription #' . $prescription->id,
                ]);
            }

            return response()->json([
                'success' => 'Prescription created successfully.', 
                'redirect' => route('prescriptions.index')
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function edit($id)
    {
        $prescription = Prescription::with(['items', 'revenue', 'problem'])->findOrFail($id);
        $patients = Patient::all();
        $medicines = Medicine::where('status', 1)->get();
        $problems = Problem::all();
        return view('prescriptions.edit', compact('prescription', 'patients', 'medicines', 'problems'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medicines' => 'required|array|min:1',
            'medicines.*' => 'required|exists:medicines,id',
            'alternate_medicines' => 'nullable|array',
            'quantities' => 'required|array|min:1',
            'timings' => 'required|array',
            'durations' => 'required|array',
            'instructions' => 'required|array',
            'medicine_notes' => 'nullable|array',
            'discount_type' => 'nullable|string|in:flat,percentage',
            'discount_value' => 'nullable|numeric|min:0',
            'received_amount' => 'nullable|numeric|min:0',
            'payment_mode' => 'nullable|string',
            'problem_id' => 'nullable|exists:problems,id',
            'youtube_links' => 'nullable|array',
            'hopi' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $prescription = Prescription::findOrFail($id);

            // Revert Old Inventory
            foreach ($prescription->items as $item) {
                $med = Medicine::find($item->medicine_id);
                if ($med) {
                    $med->increment('stock', $item->quantity);
                }
            }

            // Remove Old Transactions
            MedicineTransaction::where('reference_type', 'Prescription')
                ->where('reference_id', $prescription->id)
                ->delete();

            // Delete old items
            $prescription->items()->delete();

            // Update Prescription record
            $prescription->update([
                'patient_id' => $request->patient_id,
                'next_followup_date' => $request->next_followup_date,
                'notes' => $request->notes,
                'problem_id' => $request->problem_id,
                'youtube_links' => $request->youtube_links,
                'hopi' => $request->hopi,
                'is_edited' => true,
                'edited_by' => auth()->id(),
                'edited_at' => now(),
            ]);

            $subtotal = 0;

            foreach ($request->medicines as $key => $medicine_id) {
                $quantity = $request->quantities[$key];
                $medicine = Medicine::findOrFail($medicine_id);
                
                $subtotal += ($medicine->price * $quantity);

                if ($medicine->stock < $quantity) {
                    throw new \Exception("Insufficient stock for {$medicine->name}. Available: {$medicine->stock}");
                }

                // Create Item
                PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'medicine_id' => $medicine_id,
                    'alternate_medicine_id' => $request->alternate_medicines[$key] ?? null,
                    'timing' => $request->timings[$key] ?? null,
                    'duration' => $request->durations[$key] ?? null,
                    'quantity' => $quantity,
                    'instructions' => $request->instructions[$key] ?? null,
                    'medicine_note' => $request->medicine_notes[$key] ?? null,
                ]);

                // Deduct Stock
                $medicine->decrement('stock', $quantity);

                // Create Transaction
                MedicineTransaction::create([
                    'medicine_id' => $medicine_id,
                    'type' => 'OUT',
                    'quantity' => $quantity,
                    'reference_type' => 'Prescription',
                    'reference_id' => $prescription->id,
                    'created_by' => auth()->id(),
                ]);
            }

            // Calculate final amount
            $discount_value = $request->discount_value ?? 0;
            $finalAmount = $subtotal;
            if ($request->discount_type == 'percentage') {
                $finalAmount = $subtotal - ($subtotal * ($discount_value / 100));
            } elseif ($request->discount_type == 'flat') {
                $finalAmount = $subtotal - $discount_value;
            }

            $received = $request->received_amount !== null ? (float)$request->received_amount : $finalAmount;
            $due = $finalAmount - $received;

            // Update or Create Revenue
            $prescription->revenue()->updateOrCreate(
                ['prescription_id' => $prescription->id],
                [
                    'patient_id' => $prescription->patient_id,
                    'subtotal' => $subtotal,
                    'discount_type' => $request->discount_type,
                    'discount_value' => $request->discount_value,
                    'final_amount' => $finalAmount,
                    'received_amount' => $received,
                    'due_amount' => $due,
                    'payment_mode' => $request->payment_mode,
                    'created_by' => auth()->id(),
                ]
            );

            // Update Appointment date if changed
            if ($request->next_followup_date) {
                Appointment::updateOrCreate(
                    [
                        'patient_id' => $request->patient_id,
                        'notes' => 'Generated from Prescription #' . $prescription->id,
                    ],
                    [
                        'doctor_id' => auth()->id(),
                        'appointment_date' => $request->next_followup_date . ' 10:00:00',
                        'status' => 'Follow-up',
                    ]
                );
            }

            DB::commit();
            return response()->json([
                'success' => 'Prescription updated successfully.', 
                'redirect' => route('prescriptions.index')
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function show($id)
    {
        $prescription = Prescription::with(['patient', 'doctor', 'items.medicine', 'items.alternateMedicine', 'revenue'])->findOrFail($id);
        return view('prescriptions.show', compact('prescription'));
    }

    public function downloadPdf($id)
    {
        $prescription = Prescription::with(['patient', 'doctor', 'items.medicine', 'items.alternateMedicine', 'revenue'])->findOrFail($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('prescriptions.pdf', compact('prescription'));
        return $pdf->download('Prescription-'.$prescription->id.'.pdf');
    }

    public function viewPdf($id)
    {
        $prescription = Prescription::with(['patient', 'doctor', 'items.medicine', 'items.alternateMedicine', 'revenue'])->findOrFail($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('prescriptions.pdf', compact('prescription'));
        return $pdf->stream('Prescription-'.$prescription->id.'.pdf');
    }

    public function downloadBillingPdf($id)
    {
        $prescription = Prescription::with(['patient', 'doctor', 'revenue'])->findOrFail($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('prescriptions.billing_pdf', compact('prescription'));
        return $pdf->download('Bill-'.$prescription->id.'.pdf');
    }

    public function viewBillingPdf($id)
    {
        $prescription = Prescription::with(['patient', 'doctor', 'revenue'])->findOrFail($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('prescriptions.billing_pdf', compact('prescription'));
        return $pdf->stream('Bill-'.$prescription->id.'.pdf');
    }
}
