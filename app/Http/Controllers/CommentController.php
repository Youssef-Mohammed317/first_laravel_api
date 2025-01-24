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
    public function editComment(Request $request,$id){
        $validated = Validator::make($request->all(), [
            'content' => 'required',
            'post_id' => 'required|integer|exists:posts,id',
        ]);
        if($validated->fails()) {
            return response()->json(['error' => $validated->errors()], 422);
        }
        try{
            $comment = Comment::find($id);

            if(!$comment){
                return response()->json(['error' => 'Comment not found'], 404);
            }

            if($comment->user_id != Auth::user()->id){
                return response()->json(['error' => 'You are not authorized to view this comment'], 403);
            }
            $comment->content = $request->content;
            $comment->post_id = $request->post_id;
            $updated_comment = $comment->save();

            if($updated_comment){
                return response()->json([
                    'message' => 'Comment updated successfully',
                    'updated_comment' => $updated_comment,
                ],200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function deleteComment($id){
        try{
            $comment = Comment::find($id);
            if(!$comment){
                return response()->json(['error' => 'Comment not found'], 404);
            }
            if($comment->user_id != Auth::user()->id){
                return response()->json(['error' => 'You are not authorized to view this comment'], 403);
            }
            $comment->delete();
            return response()->json([
                'message' => 'Comment deleted successfully',
            ],200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
