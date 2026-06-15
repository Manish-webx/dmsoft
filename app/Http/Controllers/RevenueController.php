<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Revenue;

class RevenueController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        if ($request->ajax()) {
            $revenues = Revenue::with(['patient', 'prescription'])
                ->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate)
                ->latest()
                ->get();

            $totals = [
                'final_amount' => $revenues->sum('final_amount'),
                'received_amount' => $revenues->sum('received_amount'),
                'due_amount' => $revenues->sum('due_amount'),
                'count' => $revenues->count()
            ];

            return response()->json([
                'data' => $revenues,
                'totals' => $totals
            ]);
        }

        return view('revenues.index', compact('startDate', 'endDate'));
    }
}
