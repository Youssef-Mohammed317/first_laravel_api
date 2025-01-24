<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LikeController extends Controller
{
    public function likePost(Request $request){
        $validated = Validator::make($request->all(), [
            'post_id' => 'required|integer|exists:posts,id'
        ]);
        if ($validated->fails()) {
            return response()->json(['error' => $validated->errors()], 422);
        }
        $like = Like::where('user_id',Auth::user()->id)->where('post_id',$request->post_id);
        if($like->exists()){
            return response()->json([
                'message' => 'You have already liked this post'
            ]);
        }
        $like = new Like();
        $like->user_id = Auth::user()->id;
        $like->post_id = $request->post_id;
        if($like->save()){
            return response()->json([
                'message' => 'post liked successfully'
            ],200);
        }else{
            return response()->json([
                'error' => 'Failed to like post'
            ],500);
        }
    }
    public function unlikePost($id){
        $like = Like::where('user_id',Auth::user()->id)->where('post_id',$id);
        if($like->exists()){
            if($like->delete()){

                return response()->json([
                    'message' => 'post unliked successfully'
                ],200);
            }
            }else{
                return response()->json([
                    'error' => 'You have not liked this post'
                ],404);
            }
    }
}
