<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::denies('admin-access', Auth::user())) {
            abort(403, 'Unauthorized access. Admin access required.');
        }
        
        // Show all anime without pagination
        $animes = Anime::orderBy('created_at', 'desc')->get();
        
        return view('admin.index', compact('animes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Gate::denies('admin-access', Auth::user())) {
            abort(403, 'Unauthorized access. Admin access required.');
        }
        
        return view('admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Gate::denies('admin-access', Auth::user())) {
            abort(403, 'Unauthorized access. Admin access required.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'release_date' => 'nullable|date',
            'rating' => 'nullable|numeric|min:0|max:10',
            'studio' => 'nullable|string|max:255',
            'season' => 'nullable|integer',
            'episodes' => 'nullable|integer',
            'genres' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'trailer_url' => 'nullable|url',
            'official_site' => 'nullable|url',
            'status' => 'nullable|in:currently_airing,finished_airing,not_yet_aired',
            'characters' => 'nullable|string',
        ]);

        try {
            $anime = new Anime($request->except('genres', 'characters', 'image'));
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('anime', 'public');
                $anime->image_url = '/storage/' . $imagePath;
            }
            
            // Parse genres and characters from comma-separated strings to arrays
            $anime->genres = $this->parseArrayInput($request->genres);
            $anime->characters = $this->parseArrayInput($request->characters);
            
            $anime->save();

            return redirect()->route('admin.index')->with('status', 'Anime created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Anime $anime)
    {
        if (Gate::denies('admin-access', Auth::user())) {
            abort(403, 'Unauthorized access. Admin access required.');
        }
        
        return view('admin.edit', compact('anime'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Anime $anime)
    {
        if (Gate::denies('admin-access', Auth::user())) {
            abort(403, 'Unauthorized access. Admin access required.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'release_date' => 'nullable|date',
            'rating' => 'nullable|numeric|min:0|max:10',
            'studio' => 'nullable|string|max:255',
            'season' => 'nullable|integer',
            'episodes' => 'nullable|integer',
            'genres' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'trailer_url' => 'nullable|url',
            'official_site' => 'nullable|url',
            'status' => 'nullable|in:currently_airing,finished_airing,not_yet_aired',
            'characters' => 'nullable|string',
        ]);

        try {
            $anime->fill($request->except('genres', 'characters', 'image'));
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($anime->image_url && Storage::disk('public')->exists(str_replace('/storage/', '', $anime->image_url))) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $anime->image_url));
                }
                
                $imagePath = $request->file('image')->store('anime', 'public');
                $anime->image_url = '/storage/' . $imagePath;
            }
            
            // Parse genres and characters from comma-separated strings to arrays
            $anime->genres = $this->parseArrayInput($request->genres);
            $anime->characters = $this->parseArrayInput($request->characters);
            
            $anime->save();

            return redirect()->route('admin.index')->with('status', 'Anime updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Anime $anime)
    {
        if (Gate::denies('admin-access', Auth::user())) {
            abort(403, 'Unauthorized access. Admin access required.');
        }
        
        $anime->delete();

        return redirect()->route('admin.index')->with('status', 'Anime deleted successfully.');
    }
    

    
    /**
     * Parse comma-separated input string into an array
     */
    private function parseArrayInput($input)
    {
        if (is_string($input)) {
            // Split by comma and remove empty values
            $items = array_map('trim', explode(',', $input));
            return array_filter($items, function($item) {
                return !empty($item);
            });
        } elseif (is_array($input)) {
            // If already an array, return as is (after filtering empty values)
            return array_filter($input, function($item) {
                return !empty(trim($item));
            });
        } else {
            return [];
        }
    }
}