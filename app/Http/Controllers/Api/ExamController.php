<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ExamService;
use Symfony\Component\HttpFoundation\Response;

class ExamController extends Controller
{
    private $examService;

    public function __construct(ExamService $examService){
        $this->examService = $examService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            try{
                //Kiểm tra request phân trang người dùng gửi lên
                //Nếu không có thì vô config/app lấy giá trị được cài per_page
                // $limit = $request->get('limit') ?? config('app.paginate.per_page');
                // $orderBys = [];
                // if($request->get('column') && $request->get('sort')){
                //     $orderBys['column'] = $request->get('column');
                //     $orderBys['sort'] = $request->get('sort'); 
                // }
                // $examsPaginate = $this->examService->getAll($orderBys,$limit);
                // dd($examsPaginate);
                $exams = $this->examService->getBlog();
               
                $data = [];
                foreach ($exams as $key => $value) { 
                    $temp['stt'] = $key + 1;
                    $temp['title'] = $value->title;
                    $temp['desc'] = $value->des;
                    $temp['actions'] = '<a href="javascript:void(0)" data-id_edit="'.$value->id.'" class="btn btn-xs btn-warning" id="edit-blog"><i class="fa fa-eye"></i> Edit</a> <a href="javascript:void(0)" data-id="' .$value->id. '" class="btn btn-xs btn-danger btn-delete" id="delete-blog"><i class="fa fa-times"></i> Delete</a>';
                    array_push($data, $temp);
                }
                return response()->json([
                    'status'    => true,
                    'code'      =>Response::HTTP_OK,
                    'data'     => $data,
                ]);
                }catch(\Exception $e){
                    return response()->json([
                        'status'    => false,
                        'code'    => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message'   =>$e->getMessage(),
                        
                    ]);
                }
        }
        return view('test.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $exam = $this->examService->save(['title'=> $request->title, 'des'=> $request->des]);
            return response()->json([
                'status'    => true,
                'code'      =>Response::HTTP_OK,
                'data'      => $exam,
            ]);

        }catch (\Exception $e){
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
        try{
            $exam = $this->examService->findById($id);
            return response()->json([
                'status'    => true,
                'code'      =>Response::HTTP_OK,
                'data'      => $exam,
            ]);

        }catch (\Exception $e){
            return response()->json([
                'status'    => false,
                'code'    => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message'   =>$e->getMessage(),
                
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            $exam = $this->examService->findById($id);
            return response()->json([
                'status'    => true,
                'code'      =>Response::HTTP_OK,
                'data'      => $exam,
            ]);

        }catch (\Exception $e){
            return response()->json([
                'status'    => false,
                'code'    => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message'   =>$e->getMessage(),
                
            ]);
        }
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
        try{
            $exam = $this->examService->save(['title'=> $request->title, 'des'=> $request->des], $id);
            return response()->json([
                'status'    => true,
                'code'      =>Response::HTTP_OK,
                'data'      => $exam,
            ]);

        }catch (\Exception $e){
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
        try{
            $this->examService->delete([$id]);
            return response()->json([
                'status'    => true,
                'code'      =>Response::HTTP_OK,
            ]);

        }catch (\Exception $e){
            return response()->json([
                'status'    => false,
                'code'    => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message'   =>$e->getMessage(),
                
            ]);
        }
    }
}
