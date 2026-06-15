<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Problem;

class ProblemController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Problem::latest();
            if ($request->has('trashed') && $request->trashed) {
                $query->onlyTrashed();
            }
            return response()->json(['data' => $query->get()]);
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
        return response()->json(['success' => 'Problem moved to trash.']);
    }

    public function restore($id)
    {
        Problem::withTrashed()->findOrFail($id)->restore();
        return response()->json(['success' => 'Problem restored successfully.']);
    }

    public function forceDelete($id)
    {
        Problem::withTrashed()->findOrFail($id)->forceDelete();
        return response()->json(['success' => 'Problem deleted permanently.']);
    }
}
