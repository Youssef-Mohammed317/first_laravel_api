<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;

class CommentController extends Controller
{
    public function addNewComment(Request $request){
        $validated = Validator::make($request->all(), [
            'content' => 'required',
            'post_id' => 'required|integer|exists:posts,id',

        ]);
        if($validated->fails()) {
            return response()->json(['error' => $validated->errors()], 422);
        }
        try{
            $comment = new Comment();
            $comment->content = $request->content;
            $comment->post_id = $request->post_id;
            $comment->user_id = Auth::user()->id;
            if($comment->save()){
                return response()->json([
                    'message' => 'Comment created successfully',
                    // 'post' => $post
                ],200);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
