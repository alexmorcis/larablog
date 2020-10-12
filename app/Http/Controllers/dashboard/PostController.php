<?php

namespace App\Http\Controllers\dashboard;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostPost;
use Illuminate\Auth\Events\Validated;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $posts= Post::orderBy('created_at', 'desc')->paginate(4);
// select*from posts
      
        return view('dashboard.post.index',['posts'=> $posts]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.post.create',['post'=>new Post()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostPost $request)
    {
        //  dd($request->validated());
    
        // echo "Hola".$request->input('title','sin titulo');
        echo "Hola mundo: ".$request->content;
        // $request->validate(
        //     [
        //         'title'=>'required|min:5|max:500',
        //         // 'url_clean'=>'required|min:5|max:500',
        //         'content'=>'required|min:5'

        //     ]
        //     );
        // echo "Hola".$request->input('title','sin titulo');

        Post::create($request->validated());
        return back()->with('status','Post creado con existo');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //$post= Post::findOrFail();
        
        return view('dashboard.post.show',["post"=> $post]);
    
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::pluck('id','title');

        return view('dashboard.post.edit',["post"=> $post,'categories'=>$categories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePostPost $request, Post $post)
    {
        $post->update($request->validated());

        return back()->with('status','Post editado con exito');

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
       $post->delete();
       return back()->with('status','Post eliminado con exito');

    }
}
