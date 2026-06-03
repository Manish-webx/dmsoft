<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Problem;

class ProblemController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $problems = Problem::latest()->get();
            return response()->json(['data' => $problems]);
        }
        return view('problems.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'youtube_links' => 'nullable|array',
            'youtube_links.*' => 'nullable|url'
        ]);

        // Filter out empty links
        $links = array_filter($request->youtube_links ?? []);

        Problem::updateOrCreate(
            ['id' => $request->id],
            [
                'name' => $request->name,
                'youtube_links' => array_values($links)
            ]
        );

        return response()->json(['success' => 'Problem saved successfully.']);
    }

    public function edit($id)
    {
        $problem = Problem::findOrFail($id);
        return response()->json($problem);
    }

    public function destroy($id)
    {
        Problem::findOrFail($id)->delete();
        return response()->json(['success' => 'Problem deleted successfully.']);
    }
}
