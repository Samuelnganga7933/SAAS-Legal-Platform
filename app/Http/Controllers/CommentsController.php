<?php

namespace App\Http\Controllers;

use App\Models\ClientComment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function store(Request $request)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return response()->json(['error' => 'Please log in to post a comment.'], 401);
        }

        // Check if user is verified client
        if (!auth()->user()->is_verified_client) {
            return response()->json(['error' => 'Only verified clients can post testimonials.'], 403);
        }

        // Validate input
        $validated = $request->validate([
            'comment' => 'required|min:10|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Check if user already has a pending/published comment (optional - prevents spam)
        $existingComment = ClientComment::where('user_id', auth()->id())
            ->where('is_approved', false)  // Only check unapproved comments
            ->first();

        if ($existingComment) {
            // Update existing comment instead of creating a new one
            $existingComment->update([
                'comment' => $validated['comment'],
                'rating' => $validated['rating'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Testimonial updated. Waiting for approval.',
                'comment_id' => $existingComment->id
            ]);
        }

        // Create new comment
        $comment = ClientComment::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'user_role' => auth()->user()->account_type ?? 'Client',
            'location' => null, // Can be added later if needed
            'comment' => $validated['comment'],
            'rating' => $validated['rating'],
            'is_verified' => true,
            'is_approved' => false, // Requires admin approval
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your testimonial has been submitted for approval.',
            'comment_id' => $comment->id
        ]);
    }

    public function destroy($id)
    {
        $comment = ClientComment::findOrFail($id);

        // Only allow user to delete their own comment or admin
        if ($comment->user_id !== auth()->id() && !auth()->user()->is_admin) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully.'
        ]);
    }
}
