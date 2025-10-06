<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Anime $anime)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        // Check if user has already reviewed this anime
        $existingReview = Review::where('user_id', Auth::id())
            ->where('anime_id', $anime->id)
            ->first();

        if ($existingReview) {
            // Update existing review
            $existingReview->update([
                'rating' => $request->rating,
                'review' => $request->review,
            ]);
        } else {
            // Create new review
            Review::create([
                'user_id' => Auth::id(),
                'anime_id' => $anime->id,
                'rating' => $request->rating,
                'review' => $request->review,
            ]);
        }

        return redirect()->back()->with('success', 'รีวิวของคุณได้รับการบันทึกแล้ว');
    }
    
    public function destroy(Anime $anime)
    {
        $review = Review::where('user_id', Auth::id())
            ->where('anime_id', $anime->id)
            ->first();
            
        if ($review) {
            $review->delete();
        }
        
        return redirect()->back()->with('success', 'รีวิวของคุณได้รับการลบแล้ว');
    }
    
    public function like(Anime $anime)
    {
        // Get the review ID from the request
        $reviewId = request()->input('review_id');
        
        $review = Review::find($reviewId);
            
        if ($review && $review->user_id != Auth::id()) {
            $review->increment('likes');
            return response()->json(['likes' => $review->likes]);
        }
        
        return response()->json(['error' => 'Review not found or you cannot like your own review'], 404);
    }
    
    public function dislike(Anime $anime)
    {
        // Get the review ID from the request
        $reviewId = request()->input('review_id');
        
        $review = Review::find($reviewId);
            
        if ($review && $review->user_id != Auth::id()) {
            $review->increment('dislikes');
            return response()->json(['dislikes' => $review->dislikes]);
        }
        
        return response()->json(['error' => 'Review not found or you cannot dislike your own review'], 404);
    }
}
