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
    public function editPost(Request $request){
        $validated = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            // for method 1
            'id' => 'required|exists:posts,id',
        ]);
        if($validated->fails()) {
            return response()->json(['error' => $validated->errors()], 422);
        }
        try{
            $post = Post::find($request->id);
            if(!$post){
                return response()->json([
                    'error' => 'Post not found'
                ], 404);
            }
            if($post->user_id != Auth::user()->id){
                return response()->json([
                    'error' => 'You are not authorized to view this post'
                ], 403);
            }
            $post->title = $request->title;
            $post->content = $request->content;

            $post->user_id = Auth::user()->id;

            // Post::where('id', $request->id)->update($request->all());
            $updated_post = $post->save();
            if($updated_post){
                return response()->json([
                    'message' => 'Post updated successfully',
                    'updated_post' => $updated_post,
                    // 'post' => $post
                ],200);
            }

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function editPost2(Request $request, $id){
        $validated = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            // for method 1
            // 'id' => 'required|exists:posts,id',
        ]);

        if($validated->fails()) {
            return response()->json(['error' => $validated->errors()], 422);
        }
        try{
            $post = Post::find($id);
            if(!$post){
                return response()->json([
                    'error' => 'Post not found'
                ], 404);
            }
            if($post->user_id != Auth::user()->id){
                return response()->json([
                    'error' => 'You are not authorized to view this post'
                ], 403);
            }
            $post->title = $request->title;
            $post->content = $request->content;
            $updated_post = $post->save();
            if($updated_post){
                return response()->json([
                    'message' => 'Post updated successfully',
                    'updated_post' => $updated_post,
                    // 'post' => $post
                ],200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getPosts(){
        try{
            $posts = Post::all();
            if(!$posts){
                return response()->json([
                'error' => 'No posts found'
                ]);
            }
            return response()->json([
                'posts' => $posts
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getPost($id){
        try{
            // $post = Post::find($id);
            $post = Post::where('id', $id)->firstOrFail();
            if(!$post){
                return response()->json([
                    'error' => 'Post not found'
                ], 404);
            }
            return response()->json([
                'post' => $post
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deletePost($id){
        try{
            $post = Post::find($id);
            if(!$post){
                return response()->json([
                    'error' => 'Post not found'
                ], 404);
            }
            if($post->user_id != Auth::user()->id){
                return response()->json([
                    'error' => 'You are not authorized to view this post'
                ], 403);
            }
            $post->delete();
            return response()->json([
                'message' => 'Post deleted successfully'
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
