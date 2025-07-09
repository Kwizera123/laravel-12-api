<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Post;

class PostController extends Controller
{
    public function addNewPost(Request $request){
            $validated = Validator::make($request->all(),[
             'title' => 'required|string',
             'content' => 'required|string',
             
        ]);

        if($validated->fails()){
            return response()->json($validated->errors(),403);
        }

        try{
            $post = new Post();
            $post->title = $request->title;
            $post->content = $request->content;
            $post->user_id = auth()->user()->id;
            $post->save();

                    //return
        return response()->json([
            'message' => 'Post added successfully',
            'post_data' => $post
        ],status: 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

    // Edit a post
    public function editPost(Request $request){

         $validated = Validator::make($request->all(),[
             'title' => 'required|string',
             'content' => 'required|string',
             'post_id' => 'required|integer',
        ]);

        if($validated->fails()){
            return response()->json($validated->errors(),403);
        }

        try{
            $post_data = Post::find($request->post_id);

            $updatePost = $post_data->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);

            //return
        return response()->json([
            'message' => 'Post updated successfully',
            'updated_post' => $updatePost,
        ],status: 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

    }

    // Edit a post approch 2
    public function editPost2(Request $request,$post_id){

         $validated = Validator::make($request->all(),[
             'title' => 'required|string',
             'content' => 'required|string',
             'post_id' => 'required|integer',
        ]);

        if($validated->fails()){
            return response()->json($validated->errors(),403);
        }

        try{
            $post_data = Post::find($post_id);

            $updatePost = $post_data->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);

            //return
        return response()->json([
            'message' => 'Post updated successfully',
            'updated_post' => $updatePost,
        ],status: 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

    }


    //retrieve all post
    public function getAllPosts(){
        try{
            $posts = Post::all();
            return response()->json([
                'posts' => $posts
            ], 200);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 403);
        }
    }
    // Fetch sungle post
    public function getPost($post_id){
        try{
            //$post = Post::find($post_id); // first approach
            $post = Post::where('id',$post_id)->first(); // second approach
            return response()->json([
                'post' => $post
            ], 200);
        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }
    // Delete API Post
    public function deletePost(Request $request, $post_id){
        try{
            $post = Post::find($post_id);
            $post->delete();
             return response()->json([
                'message' => 'Post deleted successfully'
            ], 200);
        } catch (\Exception $e){
             return response()->json(['error' => $e->getMessage()], 403);
        }
    }
}
