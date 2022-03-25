<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ExamService;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Blog;
use Yajra\Datatables\Datatables;


class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {  
        return view('test.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:posts|max:255',
            'des' => 'required',
        ]);
       
        try{
            Blog::create($request->all());
            return response()->json([
                'success'    => true,
                'code'      =>Response::HTTP_OK,
                'message'   => 'Successfully',
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status'    => false,
                'code'    => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message'   =>$e->getMessage(),
                
            ]);
        }
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $blogs = Blog::find($id);
        return $blogs;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|unique:posts|max:255',
            'des' => 'required',
            'id' =>'required',
        ]);
        
        try{
            Blog::find(id)->update($request->all());
            return response()->json([
                'success'    => true,
                'code'      =>Response::HTTP_OK,
                'message'   => 'Update Successfully',
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status'    => false,
                'code'    => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message'   =>$e->getMessage(),
                
            ]);
        }
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Blog::find($id)->delete();
        return ['success' => true, 'message' => 'Deleted Successfully'];
    }

    // public function data(){
    //     $blogs = Blog::select(['id','title','des'])

    //     ->map(function($blogs, $key){
    //         return[
    //             'id' => $blog->id,
    //             'stt' =>($key + 1),
    //             'title' => $blogs->title,
    //             'desc' => $blogs->des,
    //         ];
    //     })->toJson();
    //     $id = 1;
    //     dd($blogs);
    //     $a = Datatables::of($blogs)
    //             ->addColumn('actions', function($blogs){
    //                 return '<a href="'. route('blog.update', $id) .'" class="btn btn-xs btn-warning"><i class="fa fa-eye"></i> View</a> <a href="javascript:void(0)" data-id="' .$id . '" class="btn btn-xs btn-danger btn-delete"><i class="fa fa-times"></i> Delete</a>';
    //             })
    //             ->removeColumn('id')
    //             ->rawColumns(['actions'])
    //             ->make();

       
    //     return re;
    // }

}
