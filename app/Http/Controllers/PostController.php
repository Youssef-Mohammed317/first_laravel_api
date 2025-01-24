<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function addNewPost(Request $request){
        $validated = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        if($validated->fails()) {
            return response()->json(['error' => $validated->errors()], 422);
        }
        try{
            $post = new Post();
            $post->title = $request->title;
            $post->content = $request->content;
            $post->user_id = Auth::user()->id;


            // $post = Post::create();

            if($post->save()){
                return response()->json([
                    'message' => 'Post created successfully',
                    // 'post' => $post
                ],200);
            }

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
