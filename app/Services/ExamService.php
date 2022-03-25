<?php

namespace App\Services;

use App\Models\Blog;

class ExamService
{
    public function save(array $data,$id = null){
        return Blog::updateOrCreate(
            ['id' => $id],
            [
                'title' =>$data['title'],
                'des' =>$data['des']
            ],
        );
    }
    public function getBlog(){
        return Blog::all();
    }

    public function getAll($orderBys =[],$limit = 10){
        $query = Blog::query();
        if($orderBys){  
            $query->orderBy($orderBys['column'],$orderBys['sort']);
        }
        return $query->paginate($limit);
    }

    public function findById($id){
        return Blog::findOrFail($id);
    }

    public function delete($id = []){
        return Blog::destroy($id);
    }

    public function searchTitle($data){
        $searchResults = Blog::where('title','LIKE', "%{$data}%")->get();
        return $searchResults;
    }

}