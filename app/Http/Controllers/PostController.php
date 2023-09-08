<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Cloudinary;
use App\Services\PostService;
use App\Models\Image;

class PostController extends Controller
{
    public function index(Post $post)
    {
        return view('posts.index')->with(['posts' => $post->getPaginateByLimit()]);
    }
    
    public function show(Post $post)
    {
        return view('posts.show')->with(['post' => $post]);
    }
    
   public function create()
    {
        return view('posts.create');  
    }

    public function store(Request $request, Post $post)
    {
        if (auth()->check()) {
            $input = $request['post'];
            $input += ['user_id' => $request->user()->id];
            
            $likeCount = auth()->user()->likedPosts->count();
            $input['like_count'] = $likeCount;
            
            $post->fill($input)->save();
            
            foreach ($request->file('images') as $upload_image){
                $image_path = Cloudinary::upload($upload_image->getRealPath())->getSecurePath();
                $image = new Image();
                $image->image_path = $image_path;
                $image->post_id = $post->id;
                $image->save();
            }
            
            
        } else {
            return redirect('/login')->with('error', 'ログインしていないため投稿できません。');
        }
    
        return redirect('/posts/' . $post->id);
    }
    
    public function edit(Post $post)
    {
        return view('posts.edit')->with(['post' => $post]);
    }
    
    public function update(Request $request, Post $post)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $input = $request->only(['new_image_path']);
            
            $input['user_id'] = $user->id;
            
            $post->fill($input)->save();
            
            if($request->hasFile('new_image')){
                foreach($request->file('new_image') as $upload_new_image){
                    $new_image_path = Cloudinary::upload($upload_new_image->getRealPath())->getSecurePath();
                    $new_image = new Image();
                    $new_image->image_path = $new_image_path;
                    $new_image->post_id = $post->id;
                    $new_image->save();
                }
            }
            
        } else {
            return redirect('/login')->with('error', 'ログインしていないため投稿できません。');
        }
    
        $post->fill($input)->save();
        
        return redirect('/posts/' . $post->id);
    }
    
    public function delete(Post $post)
    {
        $post->delete();
        return redirect('/posts');
    }
}

