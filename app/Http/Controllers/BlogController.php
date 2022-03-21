<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;

class BlogController extends Controller
{
    public function index(){
        return Blog::all();
    }

    public function show($id){
        return Blog::find($id);
    }

    public function store(Request $request){
        return Blog::create($request->all());
    }

    public function update(Request $request, $id){
        $blog = Blog::findOrFail($id);
        $blog ->update($request->all());

        return $blog;
    }

    public function delete(Request $request, $id){
        $blog = Blog::findOrFail($id);
        $blog ->delete();

        return 204;
    }

}
