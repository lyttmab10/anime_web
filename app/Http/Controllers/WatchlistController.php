<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Watchlist;
use App\Models\Anime;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    public function index()
    {
        $watchlists = Watchlist::where('user_id', Auth::id())
            ->with('anime')
            ->orderByRaw("FIELD(status, 'watching', 'on_hold', 'planned', 'dropped', 'completed')")
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Group by status
        $groupedWatchlists = $watchlists->groupBy('status');
        
        return view('watchlist.index', compact('groupedWatchlists'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'anime_id' => 'required|exists:animes,id',
            'status' => 'required|in:watching,completed,planned,on_hold,dropped',
            'progress' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        // Check if anime is already in user's watchlist
        $existingWatchlist = Watchlist::where('user_id', Auth::id())
            ->where('anime_id', $request->anime_id)
            ->first();
            
        if ($existingWatchlist) {
            // Update existing record
            $existingWatchlist->update([
                'status' => $request->status,
                'progress' => $request->progress ?? 0,
                'notes' => $request->notes,
            ]);
            
            return response()->json(['message' => 'Updated watchlist successfully', 'watchlist' => $existingWatchlist]);
        } else {
            // Create new record
            $watchlist = Watchlist::create([
                'user_id' => Auth::id(),
                'anime_id' => $request->anime_id,
                'status' => $request->status,
                'progress' => $request->progress ?? 0,
                'notes' => $request->notes,
            ]);
            
            return response()->json(['message' => 'Added to watchlist successfully', 'watchlist' => $watchlist]);
        }
    }
    
    public function update(Request $request, Watchlist $watchlist)
    {
        // Ensure user can only update their own watchlist
        if ($watchlist->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $request->validate([
            'status' => 'required|in:watching,completed,planned,on_hold,dropped',
            'progress' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        $watchlist->update([
            'status' => $request->status,
            'progress' => $request->progress ?? 0,
            'notes' => $request->notes,
        ]);
        
        return response()->json(['message' => 'Updated watchlist successfully', 'watchlist' => $watchlist]);
    }
    
    public function destroy(Watchlist $watchlist)
    {
        // Ensure user can only delete their own watchlist
        if ($watchlist->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $watchlist->delete();
        
        return response()->json(['message' => 'Removed from watchlist successfully']);
    }
    
    public function checkStatus($animeId)
    {
        $watchlist = Watchlist::where('user_id', Auth::id())
            ->where('anime_id', $animeId)
            ->first();
            
        return response()->json([
            'status' => $watchlist ? $watchlist->status : null,
            'progress' => $watchlist ? $watchlist->progress : null
        ]);
    }
}
