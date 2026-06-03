<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Medicine;
use App\Models\MedicineTransaction;
use Illuminate\Support\Facades\DB;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $medicines = Medicine::latest()->get();
            return response()->json(['data' => $medicines]);
        }
        return view('medicines.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'nullable|string',
            'price' => 'nullable|numeric',
            'status' => 'nullable|boolean',
            'items_per_strip' => 'nullable|integer|min:1',
        ]);

        $data = $request->only('name', 'unit', 'price', 'items_per_strip');
        $data['status'] = $request->has('status') ? 1 : 0;

        Medicine::updateOrCreate(
            ['id' => $request->id],
            $data
        );

        return response()->json(['success' => 'Medicine saved successfully.']);
    }

    public function edit($id)
    {
        $medicine = Medicine::findOrFail($id);
        return response()->json($medicine);
    }

    public function destroy($id)
    {
        Medicine::findOrFail($id)->delete();
        return response()->json(['success' => 'Medicine deleted successfully.']);
    }

    public function addStock(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $medicine = Medicine::findOrFail($request->medicine_id);
            $medicine->increment('stock', $request->quantity);

            MedicineTransaction::create([
                'medicine_id' => $medicine->id,
                'type' => 'IN',
                'quantity' => $request->quantity,
                'reference_type' => 'Manual',
                'created_by' => auth()->id(),
            ]);
        });

        return response()->json(['success' => 'Stock added successfully.']);
    }
}
