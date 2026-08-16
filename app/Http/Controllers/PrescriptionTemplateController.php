<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrescriptionTemplate;
use App\Models\PrescriptionTemplateItem;
use App\Models\Medicine;
use App\Models\Problem;
use Illuminate\Support\Facades\DB;

class PrescriptionTemplateController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = PrescriptionTemplate::with(['doctor', 'problem'])->latest();
            return response()->json(['data' => $query->get()]);
        }
        return view('prescription_templates.index');
    }

    public function create()
    {
        $medicines = Medicine::where('status', 1)->get();
        $problems = Problem::all();
        return view('prescription_templates.create', compact('medicines', 'problems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'template_name' => 'required|string|max:255',
            'medicines' => 'nullable|array',
            'medicines.*' => 'exists:medicines,id',
            'alternate_medicines' => 'nullable|array',
            'quantities' => 'nullable|array',
            'times' => 'nullable|array',
            'whens' => 'nullable|array',
            'frequencies' => 'nullable|array',
            'timings' => 'nullable|array',
            'durations' => 'nullable|array',
            'duration_types' => 'nullable|array',
            'instructions' => 'nullable|array',
            'medicine_notes' => 'nullable|array',
            'problem_id' => 'nullable|exists:problems,id',
            'youtube_links' => 'nullable|array',
            'hopi' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $template = PrescriptionTemplate::create([
                'name' => $request->template_name,
                'doctor_id' => auth()->id(),
                'problem_id' => $request->problem_id,
                'notes' => $request->notes,
                'hopi' => $request->hopi,
                'youtube_links' => $request->youtube_links,
            ]);

            if ($request->has('medicines') && is_array($request->medicines)) {
                foreach ($request->medicines as $key => $medicine_id) {
                    if (!$medicine_id) continue;

                    PrescriptionTemplateItem::create([
                        'prescription_template_id' => $template->id,
                        'medicine_id' => $medicine_id,
                        'alternate_medicine_id' => $request->alternate_medicines[$key] ?? null,
                        'time' => $request->times[$key] ?? null,
                        'when' => $request->whens[$key] ?? null,
                        'frequency' => $request->frequencies[$key] ?? null,
                        'timing' => $request->timings[$key] ?? null,
                        'duration' => $request->durations[$key] ?? null,
                        'duration_type' => $request->duration_types[$key] ?? null,
                        'quantity' => $request->quantities[$key] ?? 1,
                        'instructions' => $request->instructions[$key] ?? null,
                        'medicine_note' => $request->medicine_notes[$key] ?? null,
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'success' => 'Prescription template saved successfully.',
                'template' => $template,
                'redirect' => route('prescription-templates.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function show($id)
    {
        $template = PrescriptionTemplate::with(['items.medicine', 'items.alternateMedicine'])->findOrFail($id);
        return response()->json($template);
    }

    public function edit($id)
    {
        $template = PrescriptionTemplate::with(['items', 'problem'])->findOrFail($id);
        $medicines = Medicine::where('status', 1)->get();
        $problems = Problem::all();
        return view('prescription_templates.edit', compact('template', 'medicines', 'problems'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'template_name' => 'required|string|max:255',
            'medicines' => 'nullable|array',
            'medicines.*' => 'exists:medicines,id',
            'alternate_medicines' => 'nullable|array',
            'quantities' => 'nullable|array',
            'times' => 'nullable|array',
            'whens' => 'nullable|array',
            'frequencies' => 'nullable|array',
            'timings' => 'nullable|array',
            'durations' => 'nullable|array',
            'duration_types' => 'nullable|array',
            'instructions' => 'nullable|array',
            'medicine_notes' => 'nullable|array',
            'problem_id' => 'nullable|exists:problems,id',
            'youtube_links' => 'nullable|array',
            'hopi' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $template = PrescriptionTemplate::findOrFail($id);
            $template->update([
                'name' => $request->template_name,
                'problem_id' => $request->problem_id,
                'notes' => $request->notes,
                'hopi' => $request->hopi,
                'youtube_links' => $request->youtube_links,
            ]);

            $template->items()->delete();

            if ($request->has('medicines') && is_array($request->medicines)) {
                foreach ($request->medicines as $key => $medicine_id) {
                    if (!$medicine_id) continue;

                    PrescriptionTemplateItem::create([
                        'prescription_template_id' => $template->id,
                        'medicine_id' => $medicine_id,
                        'alternate_medicine_id' => $request->alternate_medicines[$key] ?? null,
                        'time' => $request->times[$key] ?? null,
                        'when' => $request->whens[$key] ?? null,
                        'frequency' => $request->frequencies[$key] ?? null,
                        'timing' => $request->timings[$key] ?? null,
                        'duration' => $request->durations[$key] ?? null,
                        'duration_type' => $request->duration_types[$key] ?? null,
                        'quantity' => $request->quantities[$key] ?? 1,
                        'instructions' => $request->instructions[$key] ?? null,
                        'medicine_note' => $request->medicine_notes[$key] ?? null,
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'success' => 'Template updated successfully.',
                'redirect' => route('prescription-templates.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function destroy($id)
    {
        PrescriptionTemplate::findOrFail($id)->delete();
        return response()->json(['success' => 'Template deleted successfully.']);
    }

    public function duplicate($id)
    {
        try {
            DB::beginTransaction();
            $original = PrescriptionTemplate::with('items')->findOrFail($id);
            
            $newTemplate = $original->replicate();
            $newTemplate->name = $newTemplate->name . ' (Copy)';
            $newTemplate->created_at = now();
            $newTemplate->save();

            foreach ($original->items as $item) {
                $newItem = $item->replicate();
                $newItem->prescription_template_id = $newTemplate->id;
                $newItem->save();
            }
            DB::commit();
            return response()->json(['success' => 'Template duplicated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function allTemplates()
    {
        $templates = PrescriptionTemplate::orderBy('name')->get(['id', 'name']);
        return response()->json($templates);
    }
}
