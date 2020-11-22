<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Woman;

class WomanController extends Controller
{
    public function index(){
        $women = Woman::all();

        if(count($women) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $women
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],404);
    }

    public function show($id){
        $woman = Woman::find($id);

        if(!is_null($woman)){
            return response([
                'message' => 'Retrieve Product Woman Success',
                'data' => $woman
            ],200);
        }

        return response([
            'message' => 'Product Woman Not Found',
            'data' => null
        ],404);
    }

    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'nama_productW' => 'required|alphanumeric',
            'harga_productW' => 'required|numeric',
            'deskripsi_productW' => 'required|alphanumeric',
            'kategori' => 'required|alpha',
            'stok' => 'required|numeric',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);
        
        if($request->hasfile('image'))
        {
            $file = $request->file('image');
            $filename = time().$file->getClientOriginalName();
            $path = base_path().'/public/uploads/woman/'.$filename;
            $file->move($path,$filename);

        }

        $woman = new Woman;
        $woman->nama_productW = $request->nama_productW;
        $woman->harga_productW = $request->harga_productW;
        $woman->deskripsi_productW = $request->deskripsi_productW;
        $woman->gambar_productW = $path;
        $woman->kategori = $request->kategori;
        $woman->stok = $request->stok;
        $woman->save();
        
        return response([
            'message' => 'Add Product Woman Success',
            'data' => $woman,
        ],200);
    }

    public function destroy($id){
        $woman = Woman::find($id);

        if(is_null($man)){
            return response([
                'message' => 'Product Woman Not Found',
                'data' => $null
            ],404);
        }

        if($woman->delete()){
            return response([
            'message' => 'Delete Product Woman Success',
            'data' => $woman,
            ],200);
        } 
        
        return response([
            'message' => 'Delete Product Woman Failed',
            'data' => null,
        ],400);
    }

    public function update(Request $request, $id){
        $woman = Woman::find($id);
        if(is_null($woman)){
            return response([
                'message' => 'Product Woman Not Found',
                'data' => $null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_productW' => 'required|alphanumeric',
            'harga_productW' => 'required|numeric',
            'deskripsi_productW' => 'required|alphanumeric',
            'kategori' => 'required|alpha',
            'stok' => 'required|numeric',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        $woman->nama_productW = $updateData['nama_productW'];
        $woman->harga_productW = $updateData['harga_productW'];
        $woman->deskripsi_productW = $updateData['deskripsi_productW'];
        $woman->kategori = $updateData['kategori'];
        $woman->stok = $updateData['stok'];

        if($request->hasfile('image'))
        {
            $file = $request->file('image');
            $filename = time().$file->getClientOriginalName();
            $path = base_path().'/public/uploads/woman/'.$filename;
            $file->move($path,$filename);

            $woman->gambar_productW = $path;
        }

        if($woman->save()){
            return response([
            'message' => 'Update Product Woman Success',
            'data' => $woman,
            ],200);
        } 
        
        return response([
            'message' => 'Update Product Woman Failed',
            'data' => null,
        ],400);
    }
}
