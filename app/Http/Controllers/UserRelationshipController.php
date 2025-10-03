<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserRelationship;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRelationshipController extends Controller
{
    // Follow a user
    public function follow(Request $request, $userId)
    {
        $userToFollow = User::findOrFail($userId);
        
        if ($userToFollow->id == Auth::id()) {
            return response()->json(['message' => 'คุณไม่สามารถติดตามตัวเองได้'], 400);
        }
        
        // Check if relationship already exists
        $existingRelationship = UserRelationship::where('user_id', Auth::id())
            ->where('related_user_id', $userToFollow->id)
            ->where('type', 'follow')
            ->first();
            
        if ($existingRelationship) {
            if ($existingRelationship->status === 'accepted') {
                return response()->json(['message' => 'คุณติดตามผู้ใช้นี้อยู่แล้ว'], 400);
            } else {
                // Request already sent but pending
                return response()->json(['message' => 'ส่งคำขอติดตามไปแล้ว'], 400);
            }
        }
        
        // Create the follow relationship
        UserRelationship::create([
            'user_id' => Auth::id(),
            'related_user_id' => $userToFollow->id,
            'type' => 'follow',
            'status' => 'accepted', // For following, we don't need approval
        ]);
        
        return response()->json(['message' => 'ติดตามผู้ใช้สำเร็จ']);
    }
    
    // Unfollow a user
    public function unfollow($userId)
    {
        $relationship = UserRelationship::where('user_id', Auth::id())
            ->where('related_user_id', $userId)
            ->where('type', 'follow')
            ->first();
            
        if (!$relationship) {
            return response()->json(['message' => 'ไม่พบความสัมพันธ์'], 404);
        }
        
        $relationship->delete();
        
        return response()->json(['message' => 'เลิกติดตามผู้ใช้สำเร็จ']);
    }
    
    // Send friend request
    public function sendFriendRequest($userId)
    {
        $userToFriend = User::findOrFail($userId);
        
        if ($userToFriend->id == Auth::id()) {
            return response()->json(['message' => 'คุณไม่สามารถส่งคำขอเป็นเพื่อนกับตัวเองได้'], 400);
        }
        
        // Check if relationship already exists
        $existingRelationship = UserRelationship::where('user_id', Auth::id())
            ->where('related_user_id', $userToFriend->id)
            ->where('type', 'friend')
            ->first();
            
        $existingReverseRelationship = UserRelationship::where('user_id', $userToFriend->id)
            ->where('related_user_id', Auth::id())
            ->where('type', 'friend')
            ->first();
            
        if ($existingRelationship || $existingReverseRelationship) {
            if (($existingRelationship && $existingRelationship->status === 'accepted') ||
                ($existingReverseRelationship && $existingReverseRelationship->status === 'accepted')) {
                return response()->json(['message' => 'คุณเป็นเพื่อนกับผู้ใช้นี้อยู่แล้ว'], 400);
            } else {
                return response()->json(['message' => 'ส่งคำขอเป็นเพื่อนไปแล้ว'], 400);
            }
        }
        
        // Create the friend request
        UserRelationship::create([
            'user_id' => Auth::id(),
            'related_user_id' => $userToFriend->id,
            'type' => 'friend',
            'status' => 'pending',
        ]);
        
        return response()->json(['message' => 'ส่งคำขอเป็นเพื่อนสำเร็จ']);
    }
    
    // Accept friend request
    public function acceptFriendRequest($requestId)
    {
        $relationship = UserRelationship::where('id', $requestId)
            ->where('related_user_id', Auth::id()) // Auth user is the one receiving the request
            ->where('type', 'friend')
            ->where('status', 'pending')
            ->first();
            
        if (!$relationship) {
            return response()->json(['message' => 'ไม่พบคำขอเป็นเพื่อน'], 404);
        }
        
        $relationship->update(['status' => 'accepted']);
        
        // Create reciprocal relationship
        UserRelationship::firstOrCreate([
            'user_id' => $relationship->relatedUser->id,
            'related_user_id' => Auth::id(),
            'type' => 'friend',
            'status' => 'accepted',
        ]);
        
        return response()->json(['message' => 'ยอมรับคำขอเป็นเพื่อนสำเร็จ']);
    }
    
    // Decline friend request
    public function declineFriendRequest($requestId)
    {
        $relationship = UserRelationship::where('id', $requestId)
            ->where('related_user_id', Auth::id()) // Auth user is the one receiving the request
            ->where('type', 'friend')
            ->where('status', 'pending')
            ->first();
            
        if (!$relationship) {
            return response()->json(['message' => 'ไม่พบคำขอเป็นเพื่อน'], 404);
        }
        
        $relationship->delete();
        
        return response()->json(['message' => 'ปฏิเสธคำขอเป็นเพื่อนสำเร็จ']);
    }
    
    // Cancel friend request
    public function cancelFriendRequest($userId)
    {
        $relationship = UserRelationship::where('user_id', Auth::id())
            ->where('related_user_id', $userId)
            ->where('type', 'friend')
            ->where('status', 'pending')
            ->first();
            
        if (!$relationship) {
            return response()->json(['message' => 'ไม่พบคำขอเป็นเพื่อน'], 404);
        }
        
        $relationship->delete();
        
        return response()->json(['message' => 'ยกเลิกคำขอเป็นเพื่อนสำเร็จ']);
    }
    
    // Remove friend
    public function removeFriend($userId)
    {
        $relationship = UserRelationship::where('user_id', Auth::id())
            ->where('related_user_id', $userId)
            ->where('type', 'friend')
            ->where('status', 'accepted')
            ->first();
            
        $reverseRelationship = UserRelationship::where('user_id', $userId)
            ->where('related_user_id', Auth::id())
            ->where('type', 'friend')
            ->where('status', 'accepted')
            ->first();
            
        if (!$relationship && !$reverseRelationship) {
            return response()->json(['message' => 'ไม่พบความสัมพันธ์เป็นเพื่อน'], 404);
        }
        
        // Remove both relationships
        if ($relationship) {
            $relationship->delete();
        }
        if ($reverseRelationship) {
            $reverseRelationship->delete();
        }
        
        return response()->json(['message' => 'ลบเพื่อนสำเร็จ']);
    }
    
    // Get user's friend list
    public function friends($userId)
    {
        $user = User::findOrFail($userId);
        $friends = $user->friends()->with('relatedUser')->get();
        
        return response()->json($friends);
    }
    
    // Get user's following list
    public function following($userId)
    {
        $user = User::findOrFail($userId);
        $following = $user->following()->with('relatedUser')->get();
        
        return response()->json($following);
    }
    
    // Get user's followers list
    public function followers($userId)
    {
        $user = User::findOrFail($userId);
        $followers = $user->followers()->with('user')->get();
        
        return response()->json($followers);
    }
    
    // Get pending friend requests for the authenticated user
    public function pendingRequests()
    {
        $requests = Auth::user()->followRequests()->with('user')->get();
        
        return response()->json($requests);
    }
    
    // Display friends page
    public function friendsPage($userId)
    {
        $user = User::findOrFail($userId);
        
        if ($user->id != Auth::id()) {
            // If viewing another user's profile, show only public info
            $friends = $user->friends()->with('relatedUser')->get();
            $isOwnProfile = false;
        } else {
            // If viewing own profile, show additional options
            $friends = $user->friends()->with('relatedUser')->get();
            $isOwnProfile = true;
        }
        
        return view('friends.index', compact('user', 'friends', 'isOwnProfile'));
    }
    
    // Display followers page
    public function followersPage($userId)
    {
        $user = User::findOrFail($userId);
        $followers = $user->followers()->with('user')->get();
        
        return view('friends.followers', compact('user', 'followers'));
    }
    
    // Display following page
    public function followingPage($userId)
    {
        $user = User::findOrFail($userId);
        $following = $user->following()->with('relatedUser')->get();
        
        return view('friends.following', compact('user', 'following'));
    }
    
    // Display friend requests page
    public function friendRequestsPage()
    {
        $requests = Auth::user()->followRequests()->with('user')->get();
        
        return view('friends.requests', compact('requests'));
    }
}
